<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validador XML/XSD con Documentación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--dark-color);
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 16px;
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.2rem;
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        @media (min-width: 992px) {
            .content-wrapper {
                grid-template-columns: 2fr 1fr;
            }
        }

        .card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .card-header i {
            margin-right: 10px;
        }

        .card-body {
            padding: 25px;
        }

        .upload-area {
            border: 2px dashed #ddd;
            border-radius: var(--border-radius);
            padding: 40px;
            text-align: center;
            margin-bottom: 25px;
            transition: var(--transition);
            cursor: pointer;
        }

        .upload-area:hover,
        .upload-area.dragover {
            border-color: var(--secondary-color);
            background-color: rgba(52, 152, 219, 0.05);
        }

        .upload-area i {
            font-size: 48px;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .upload-area h3 {
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .upload-area p {
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .file-input {
            display: none;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-secondary {
            background-color: var(--light-color);
            color: var(--dark-color);
            border: 1px solid #ddd;
        }

        .validation-result {
            margin-top: 30px;
            display: none;
        }

        .validation-result.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: var(--border-radius);
        }

        .result-header.success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }

        .result-header.error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        .result-header i {
            font-size: 24px;
            margin-right: 15px;
        }

        .result-header h3 {
            font-size: 1.5rem;
        }

        .error-container {
            margin-top: 20px;
        }

        .error-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .error-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: var(--transition);
        }

        .error-tab:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .error-tab.active {
            border-bottom-color: var(--secondary-color);
            font-weight: 600;
        }

        .error-tab i {
            margin-right: 8px;
        }

        .error-content {
            display: none;
        }

        .error-content.active {
            display: block;
        }

        .error-item {
            background-color: #f8f9fa;
            border-left: 4px solid;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
        }

        .error-item.warning {
            border-left-color: var(--warning-color);
        }

        .error-item.error {
            border-left-color: var(--danger-color);
        }

        .error-item.fatal {
            border-left-color: #8e44ad;
        }

        .error-item.has-documentation {
            border-left-width: 6px;
        }

        .error-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .error-type {
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .error-type i {
            margin-right: 8px;
        }

        .error-location {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .error-context {
            background-color: #e8f4fd;
            border: 1px solid #b6d7e8;
            border-radius: var(--border-radius);
            padding: 10px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: var(--dark-color);
        }

        .error-message {
            font-family: 'Courier New', monospace;
            background-color: white;
            padding: 12px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            margin-top: 8px;
        }

        .error-documentation {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: var(--border-radius);
            padding: 12px;
            margin-top: 10px;
        }

        .documentation-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: #856404;
            font-size: 0.9rem;
        }

        .documentation-header i {
            color: #856404;
        }

        .documentation-content {
            color: #856404;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .info-card {
            margin-bottom: 20px;
        }

        .info-list {
            list-style-type: none;
        }

        .info-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list i {
            color: var(--secondary-color);
            margin-right: 10px;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: var(--border-radius);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            z-index: 1000;
            box-shadow: var(--box-shadow);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background-color: var(--success-color);
        }

        .notification.error {
            background-color: var(--danger-color);
        }

        .notification i {
            font-size: 1.2rem;
        }

        .file-info {
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: var(--border-radius);
            padding: 15px;
            margin-top: 20px;
            display: none;
        }

        .file-info.show {
            display: block;
        }

        .file-info h4 {
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .file-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .file-detail {
            display: flex;
            align-items: center;
        }

        .file-detail i {
            margin-right: 8px;
            color: var(--secondary-color);
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid var(--secondary-color);
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .documentation-feature {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: var(--border-radius);
            margin-top: 15px;
        }

        .documentation-feature h4 {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .documentation-feature ul {
            padding-left: 20px;
        }

        .documentation-feature li {
            margin-bottom: 5px;
        }

        @media (max-width: 991px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .header h1 {
                font-size: 1.8rem;
            }

            .upload-area {
                padding: 20px;
            }

            .error-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .error-location {
                margin-top: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <header class="header">
            <h1><i class="fas fa-check-circle"></i> Validador XML/XSD Avanzado</h1>
            <p>Herramienta para validar archivos XML contra esquemas XSD (CFDI 4.0) con documentación integrada</p>
        </header>

        <div class="content-wrapper">
            <!-- Columna principal -->
            <div class="main-column">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-upload"></i> Cargar Archivo XML
                    </div>
                    <div class="card-body">
                        <div class="upload-area" id="upload-area">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <h3>Arrastra y suelta tu archivo XML aquí</h3>
                            <p>o haz clic para seleccionar un archivo</p>
                            <button type="button" class="btn btn-primary" id="select-file-btn">Seleccionar Archivo
                            </button>
                            <input type="file" id="xml-file" class="file-input" accept=".xml">
                        </div>

                        <div class="file-info" id="file-info">
                            <h4><i class="fas fa-info-circle"></i> Información del Archivo</h4>
                            <div class="file-details">
                                <div class="file-detail">
                                    <i class="fas fa-file"></i>
                                    <span id="file-name">-</span>
                                </div>
                                <div class="file-detail">
                                    <i class="fas fa-weight"></i>
                                    <span id="file-size">-</span>
                                </div>
                                <div class="file-detail">
                                    <i class="fas fa-calendar"></i>
                                    <span id="file-date">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="actions"
                            style="margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                            <button type="button" class="btn btn-primary" id="validate-btn" disabled>
                                <i class="fas fa-check-double"></i> Validar XML
                            </button>
                            <button type="button" class="btn btn-secondary" id="reset-btn">
                                <i class="fas fa-redo"></i> Restablecer
                            </button>
                        </div>

                        <div class="loading" id="loading">
                            <div class="spinner"></div>
                            <p>Validando archivo XML contra XSD...</p>
                        </div>

                        <div class="validation-result" id="validation-result">
                            <!-- Los resultados se insertarán aquí dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- Tipos de Errores debajo de Cargar Archivo XML -->
                <div class="card info-card">
                    <div class="card-header">
                        <i class="fas fa-exclamation-triangle"></i> Tipos de Errores
                    </div>
                    <div class="card-body">
                        <div class="error-item warning">
                            <div class="error-type">
                                <i class="fas fa-exclamation-triangle"></i> Advertencia
                            </div>
                            <p>Problemas que no impiden la validación pero podrían causar problemas.</p>
                        </div>
                        <div class="error-item error" style="margin-top: 15px;">
                            <div class="error-type">
                                <i class="fas fa-times-circle"></i> Error
                            </div>
                            <p>Problemas que violan las reglas del esquema pero no son críticos.</p>
                        </div>
                        <div class="error-item fatal" style="margin-top: 15px;">
                            <div class="error-type">
                                <i class="fas fa-bomb"></i> Error Fatal
                            </div>
                            <p>Errores graves que impiden completamente la validación del documento.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna lateral -->
            <div class="sidebar">
                <div class="card info-card">
                    <div class="card-header">
                        <i class="fas fa-question-circle"></i> Ayuda
                    </div>
                    <div class="card-body">
                        <h5>¿Cómo usar?</h5>
                        <ol>
                            <li>Selecciona o arrastra un archivo XML</li>
                            <li>Haz clic en "Validar XML"</li>
                            <li>Revisa los resultados de la validación</li>
                            <li>Corrige los errores usando la documentación proporcionada</li>
                        </ol>
                        <h5 style="margin-top: 15px;">Validación con Documentación</h5>
                        <p>Esta versión realiza una validación real contra el esquema XSD en el servidor y muestra
                            documentación contextual para ayudarte a entender y corregir los errores.</p>
                    </div>
                </div>

                <div class="card info-card">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i> Información de Validación
                    </div>
                    <div class="card-body">
                        <p>Esta herramienta valida archivos XML contra el esquema XSD <strong>cfdv40.xsd.xml</strong>,
                            que es el estándar para Comprobantes Fiscales Digitales versión 4.0.</p>
                        <br>
                        <p>La validación verifica:</p>
                        <ul class="info-list">
                            <li><i class="fas fa-check"></i> Estructura del documento</li>
                            <li><i class="fas fa-check"></i> Tipos de datos</li>
                            <li><i class="fas fa-check"></i> Atributos requeridos</li>
                            <li><i class="fas fa-check"></i> Restricciones de valores</li>
                            <li><i class="fas fa-check"></i> Relaciones entre elementos</li>
                        </ul>

                        <div class="documentation-feature">
                            <h4><i class="fas fa-book"></i> Nueva Funcionalidad</h4>
                            <p>Ahora incluye documentación del XSD para cada error:</p>
                            <ul>
                                <li>Explicación de elementos y atributos</li>
                                <li>Requisitos y restricciones</li>
                                <li>Información contextual del esquema</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elementos del DOM
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('xml-file');
            const selectFileBtn = document.getElementById('select-file-btn');
            const validateBtn = document.getElementById('validate-btn');
            const resetBtn = document.getElementById('reset-btn');
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const fileDate = document.getElementById('file-date');
            const validationResult = document.getElementById('validation-result');
            const loading = document.getElementById('loading');

            // Event Listeners
            selectFileBtn.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', handleFileSelect);
            validateBtn.addEventListener('click', validateXML);
            resetBtn.addEventListener('click', resetForm);

            // Arrastrar y soltar
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');

                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileSelect();
                }
            });

            // Manejar selección de archivo
            function handleFileSelect() {
                if (fileInput.files.length === 0) return;

                const file = fileInput.files[0];

                // Validar tipo de archivo
                if (file.type !== 'text/xml' && !file.name.endsWith('.xml')) {
                    showNotification('Por favor, selecciona un archivo XML válido', 'error');
                    resetForm();
                    return;
                }

                // Mostrar información del archivo
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileDate.textContent = new Date(file.lastModified).toLocaleString();
                fileInfo.classList.add('show');

                // Habilitar botón de validación
                validateBtn.disabled = false;

                // Ocultar resultados anteriores
                validationResult.classList.remove('show');
            }

            // Validar XML contra XSD (usando PHP en el servidor)
            async function validateXML() {
                if (fileInput.files.length === 0) return;

                const file = fileInput.files[0];
                const reader = new FileReader();

                // Mostrar indicador de carga
                loading.classList.add('show');
                validationResult.classList.remove('show');

                reader.onload = async function (e) {
                    try {
                        const xmlContent = e.target.result;

                        // Enviar al servidor PHP para validación real
                        const response = await fetch('validador.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ xmlContent: xmlContent })
                        });

                        const data = await response.json();

                        loading.classList.remove('show');
                        displayResults(data);

                    } catch (error) {
                        loading.classList.remove('show');
                        displayResults({
                            success: false,
                            message: 'Error al conectar con el servidor de validación',
                            errors: [{
                                level: 'fatal',
                                code: 'CONNECTION_ERROR',
                                line: 0,
                                column: 0,
                                message: error.message,
                                documentation: null
                            }]
                        });
                    }
                };

                reader.onerror = function () {
                    loading.classList.remove('show');
                    showNotification('Error al leer el archivo', 'error');
                };

                reader.readAsText(file);
            }

            // Mostrar resultados
            function displayResults(data) {
                validationResult.innerHTML = '';

                // Crear encabezado de resultados
                const resultHeader = document.createElement('div');
                resultHeader.className = `result-header ${data.success ? 'success' : 'error'}`;
                resultHeader.innerHTML = `
                    <i class="fas ${data.success ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                    <h3>${data.message}</h3>
                `;
                validationResult.appendChild(resultHeader);

                // Si hay errores, mostrarlos
                if (!data.success && data.errors.length > 0) {
                    const errorContainer = document.createElement('div');
                    errorContainer.className = 'error-container';

                    // Crear pestañas para tipos de errores
                    const errorTabs = document.createElement('div');
                    errorTabs.className = 'error-tabs';

                    // Contadores de errores por tipo
                    const errorCounts = {
                        warning: 0,
                        error: 0,
                        fatal: 0
                    };

                    data.errors.forEach(error => {
                        errorCounts[error.level]++;
                    });

                    // Crear pestañas solo para los tipos de errores que existen
                    if (errorCounts.warning > 0) {
                        const warningTab = document.createElement('div');
                        warningTab.className = 'error-tab active';
                        warningTab.dataset.level = 'warning';
                        warningTab.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Advertencias (${errorCounts.warning})`;
                        warningTab.addEventListener('click', () => showErrorTab('warning'));
                        errorTabs.appendChild(warningTab);
                    }

                    if (errorCounts.error > 0) {
                        const errorTab = document.createElement('div');
                        errorTab.className = 'error-tab';
                        if (errorCounts.warning === 0) errorTab.classList.add('active');
                        errorTab.dataset.level = 'error';
                        errorTab.innerHTML = `<i class="fas fa-times-circle"></i> Errores (${errorCounts.error})`;
                        errorTab.addEventListener('click', () => showErrorTab('error'));
                        errorTabs.appendChild(errorTab);
                    }

                    if (errorCounts.fatal > 0) {
                        const fatalTab = document.createElement('div');
                        fatalTab.className = 'error-tab';
                        if (errorCounts.warning === 0 && errorCounts.error === 0) fatalTab.classList.add('active');
                        fatalTab.dataset.level = 'fatal';
                        fatalTab.innerHTML = `<i class="fas fa-bomb"></i> Errores Fatales (${errorCounts.fatal})`;
                        fatalTab.addEventListener('click', () => showErrorTab('fatal'));
                        errorTabs.appendChild(fatalTab);
                    }

                    errorContainer.appendChild(errorTabs);

                    // Crear contenido para cada tipo de error
                    ['warning', 'error', 'fatal'].forEach(level => {
                        const errorContent = document.createElement('div');
                        errorContent.className = `error-content ${level === 'warning' ? 'active' : ''}`;
                        errorContent.dataset.level = level;

                        // Filtrar errores por nivel
                        const levelErrors = data.errors.filter(error => error.level === level);

                        levelErrors.forEach(error => {
                            const errorItem = document.createElement('div');
                            errorItem.className = `error-item ${level}`;

                            // Añadir clase si tiene documentación
                            if (error.documentation) {
                                errorItem.classList.add('has-documentation');
                            }

                            const errorHeader = document.createElement('div');
                            errorHeader.className = 'error-header';

                            const errorType = document.createElement('div');
                            errorType.className = 'error-type';

                            let icon, levelText;
                            switch (level) {
                                case 'warning':
                                    icon = 'fa-exclamation-triangle';
                                    levelText = 'Advertencia';
                                    break;
                                case 'error':
                                    icon = 'fa-times-circle';
                                    levelText = 'Error';
                                    break;
                                case 'fatal':
                                    icon = 'fa-bomb';
                                    levelText = 'Error Fatal';
                                    break;
                            }

                            errorType.innerHTML = `<i class="fas ${icon}"></i> ${levelText} ${error.code}`;

                            const errorLocation = document.createElement('div');
                            errorLocation.className = 'error-location';
                            errorLocation.innerHTML = `<i class="fas fa-map-marker-alt"></i> Línea ${error.line}, Columna ${error.column}`;

                            errorHeader.appendChild(errorType);
                            errorHeader.appendChild(errorLocation);

                            // Añadir contexto (elemento y atributo)
                            if (error.element || error.attribute) {
                                const errorContext = document.createElement('div');
                                errorContext.className = 'error-context';
                                let contextText = '';
                                if (error.element) {
                                    contextText += `<strong>Elemento:</strong> ${error.element}`;
                                }
                                if (error.attribute) {
                                    if (contextText) contextText += ' | ';
                                    contextText += `<strong>Atributo:</strong> ${error.attribute}`;
                                }
                                errorContext.innerHTML = contextText;
                                errorItem.appendChild(errorContext);
                            }

                            const errorMessage = document.createElement('div');
                            errorMessage.className = 'error-message';
                            errorMessage.textContent = error.message;

                            errorItem.appendChild(errorHeader);
                            errorItem.appendChild(errorMessage);

                            // Añadir documentación si está disponible
                            if (error.documentation) {
                                const errorDocumentation = document.createElement('div');
                                errorDocumentation.className = 'error-documentation';
                                errorDocumentation.innerHTML = `
                                    <div class="documentation-header">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Documentación del XSD:</strong>
                                    </div>
                                    <div class="documentation-content">${error.documentation}</div>
                                `;
                                errorItem.appendChild(errorDocumentation);
                            }

                            errorContent.appendChild(errorItem);
                        });

                        errorContainer.appendChild(errorContent);
                    });

                    validationResult.appendChild(errorContainer);
                }

                validationResult.classList.add('show');

                // Mostrar notificación
                showNotification(data.message, data.success ? 'success' : 'error');
            }

            // Mostrar pestaña de errores específica
            function showErrorTab(level) {
                // Actualizar pestañas activas
                document.querySelectorAll('.error-tab').forEach(tab => {
                    tab.classList.remove('active');
                    if (tab.dataset.level === level) {
                        tab.classList.add('active');
                    }
                });

                // Actualizar contenido activo
                document.querySelectorAll('.error-content').forEach(content => {
                    content.classList.remove('active');
                    if (content.dataset.level === level) {
                        content.classList.add('active');
                    }
                });
            }

            // Restablecer formulario
            function resetForm() {
                fileInput.value = '';
                fileInfo.classList.remove('show');
                validationResult.classList.remove('show');
                validateBtn.disabled = true;
            }

            // Formatear tamaño de archivo
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';

                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Mostrar notificación
            function showNotification(message, type) {
                // Crear elemento de notificación
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.innerHTML = `
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                `;

                // Añadir al DOM
                document.body.appendChild(notification);

                // Mostrar con animación
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                // Ocultar después de 3 segundos
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }
        });
    </script>
</body>

</html>