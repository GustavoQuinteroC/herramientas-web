<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

class XSDValidator {
    private $xsdFile;
    private $xsdDoc;

    public function __construct($xsdFile) {
        $this->xsdFile = $xsdFile;
        $this->xsdDoc = new DOMDocument();
        $this->xsdDoc->load($xsdFile);
    }

    public function getDocumentationForElement($elementName) {
        $xpath = new DOMXPath($this->xsdDoc);
        $xpath->registerNamespace('xs', 'http://www.w3.org/2001/XMLSchema');

        // Buscar elemento por nombre
        $elements = $xpath->query("//xs:element[@name='$elementName']");

        if ($elements->length > 0) {
            $element = $elements->item(0);
            return $this->extractDocumentation($element);
        }

        return null;
    }

    public function getDocumentationForAttribute($elementName, $attributeName) {
        $xpath = new DOMXPath($this->xsdDoc);
        $xpath->registerNamespace('xs', 'http://www.w3.org/2001/XMLSchema');

        // Buscar atributo dentro del elemento
        $attributes = $xpath->query("//xs:element[@name='$elementName']//xs:attribute[@name='$attributeName']");

        if ($attributes->length > 0) {
            $attribute = $attributes->item(0);
            return $this->extractDocumentation($attribute);
        }

        return null;
    }

    public function getDocumentationForComplexType($typeName) {
        $xpath = new DOMXPath($this->xsdDoc);
        $xpath->registerNamespace('xs', 'http://www.w3.org/2001/XMLSchema');

        $types = $xpath->query("//xs:complexType[@name='$typeName']");

        if ($types->length > 0) {
            $type = $types->item(0);
            return $this->extractDocumentation($type);
        }

        return null;
    }

    private function extractDocumentation($node) {
        $xpath = new DOMXPath($this->xsdDoc);
        $xpath->registerNamespace('xs', 'http://www.w3.org/2001/XMLSchema');

        $documentation = $xpath->query('.//xs:annotation/xs:documentation', $node);

        if ($documentation->length > 0) {
            return trim($documentation->item(0)->nodeValue);
        }

        return null;
    }

    public function findElementByPath($path) {
        $xpath = new DOMXPath($this->xsdDoc);
        $xpath->registerNamespace('xs', 'http://www.w3.org/2001/XMLSchema');

        // Simplificar la búsqueda - buscar elementos que puedan estar en el path
        $parts = explode('/', $path);
        $elementName = end($parts);

        $elements = $xpath->query("//xs:element[@name='$elementName']");

        if ($elements->length > 0) {
            return $this->extractDocumentation($elements->item(0));
        }

        return null;
    }

    public function findAttributeDocumentation($elementName, $attributeName) {
        return $this->getDocumentationForAttribute($elementName, $attributeName);
    }
}

function validarXML($xmlContent, $xsdFile) {
    libxml_use_internal_errors(true);

    $resultado = [
        'success' => false,
        'message' => '',
        'errors' => []
    ];

    try {
        $dom = new DOMDocument();

        // Cargar el XML desde el contenido
        if (!$dom->loadXML($xmlContent)) {
            throw new Exception("No se pudo cargar el XML");
        }

        // Inicializar el validador de documentación
        $xsdValidator = new XSDValidator($xsdFile);

        // Validar contra el XSD
        if ($dom->schemaValidate($xsdFile)) {
            $resultado['success'] = true;
            $resultado['message'] = "✅ El XML es válido según el XSD.";
        } else {
            $resultado['message'] = "❌ El XML NO es válido:";
            $errors = libxml_get_errors();

            foreach ($errors as $error) {
                $level = '';
                switch ($error->level) {
                    case LIBXML_ERR_WARNING:
                        $level = 'warning';
                        break;
                    case LIBXML_ERR_ERROR:
                        $level = 'error';
                        break;
                    case LIBXML_ERR_FATAL:
                        $level = 'fatal';
                        break;
                    default:
                        $level = 'error';
                }

                $errorData = [
                    'level' => $level,
                    'code' => $error->code,
                    'line' => $error->line,
                    'column' => $error->column,
                    'message' => trim($error->message),
                    'documentation' => null
                ];

                // Extraer información adicional del mensaje de error
                $additionalInfo = extractErrorInfo($error->message);
                if ($additionalInfo) {
                    $errorData['element'] = $additionalInfo['element'];
                    $errorData['attribute'] = $additionalInfo['attribute'];

                    // Buscar documentación en el XSD
                    if ($additionalInfo['attribute']) {
                        $errorData['documentation'] = $xsdValidator->findAttributeDocumentation(
                            $additionalInfo['element'],
                            $additionalInfo['attribute']
                        );
                    } else {
                        $errorData['documentation'] = $xsdValidator->findElementByPath($additionalInfo['element']);
                    }
                }

                $resultado['errors'][] = $errorData;
            }

            libxml_clear_errors();
        }

    } catch (Exception $e) {
        $resultado['message'] = "❌ Error al procesar el XML: " . $e->getMessage();
        $resultado['errors'][] = [
            'level' => 'fatal',
            'code' => 'PROCESSING_ERROR',
            'line' => 0,
            'column' => 0,
            'message' => $e->getMessage(),
            'documentation' => null
        ];
    }

    return $resultado;
}

function extractErrorInfo($errorMessage) {
    $info = [
        'element' => null,
        'attribute' => null
    ];

    // Patrones para extraer información del error
    if (preg_match("/Element '([^']+)'/", $errorMessage, $matches)) {
        $info['element'] = $matches[1];
    }

    if (preg_match("/attribute '([^']+)'/", $errorMessage, $matches)) {
        $info['attribute'] = $matches[1];
    }

    // Si no encontramos por el patrón anterior, intentamos otro
    if (!$info['element'] && preg_match("/'([^']+)':/", $errorMessage, $matches)) {
        $info['element'] = $matches[1];
    }

    return $info;
}

// Procesar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['xmlContent']) || empty($input['xmlContent'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se proporcionó contenido XML',
            'errors' => []
        ]);
        exit;
    }

    $xsdFile = 'cfdv40.xsd.xml';

    if (!file_exists($xsdFile)) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró el archivo XSD',
            'errors' => []
        ]);
        exit;
    }

    $resultado = validarXML($input['xmlContent'], $xsdFile);
    echo json_encode($resultado);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido',
        'errors' => []
    ]);
}
?>