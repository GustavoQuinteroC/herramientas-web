document.addEventListener('DOMContentLoaded', function() {
  const qrCode = document.getElementById('qr-code');
  const ssidInput = document.getElementById('ssid');
  const passwordInput = document.getElementById('password');
  const encryptionSelect = document.getElementById('encryption');
  const hiddenCheckbox = document.getElementById('hidden');
  const generateBtn = document.getElementById('generate-btn');
  const printBtn = document.getElementById('print-btn');
  const downloadBtn = document.getElementById('download-btn');
  const togglePasswordBtn = document.getElementById('toggle-password');
  const loadingOverlay = document.getElementById('loading');

  // Función para generar el código QR
  function generateQR() {
    // Mostrar indicador de carga
    loadingOverlay.classList.add('active');

    const ssid = ssidInput.value.trim();
    const password = passwordInput.value;
    const encryption = encryptionSelect.value;
    const hidden = hiddenCheckbox.checked;

    // Validar que el SSID no esté vacío
    if (!ssid) {
      showNotification('Por favor, introduce un nombre de red (SSID)', 'warning');
      loadingOverlay.classList.remove('active');
      return;
    }

    // Construir la cadena WiFi
    let wifiString = `WIFI:T:${encryption};S:${ssid};`;

    // Añadir contraseña solo si no es una red abierta
    if (encryption !== 'nopass') {
      wifiString += `P:${password};`;
    }

    // Añadir si es una red oculta
    if (hidden) {
      wifiString += 'H:true;';
    }

    wifiString += ';';

    // Actualizar la imagen del código QR
    qrCode.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(wifiString)}`;

    // Ocultar indicador de carga después de un breve retraso
    setTimeout(() => {
      loadingOverlay.classList.remove('active');
      showNotification('Código QR generado correctamente', 'success');
    }, 500);
  }

  // Función para mostrar notificaciones
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

  // Función para imprimir el código QR
  function printQR() {
    window.print();
  }

  // Función para descargar el código QR
  function downloadQR() {
    const link = document.createElement('a');
    link.href = qrCode.src;
    link.download = `wifi-qr-${ssidInput.value || 'network'}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showNotification('Código QR descargado', 'success');
  }

  // Función para mostrar/ocultar contraseña
  function togglePassword() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    // Cambiar icono
    const icon = togglePasswordBtn.querySelector('i');
    icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
  }

  // Event listeners
  generateBtn.addEventListener('click', generateQR);
  printBtn.addEventListener('click', printQR);
  downloadBtn.addEventListener('click', downloadQR);
  togglePasswordBtn.addEventListener('click', togglePassword);

  // Actualizar QR automáticamente al cambiar los campos
  ssidInput.addEventListener('input', generateQR);
  passwordInput.addEventListener('input', generateQR);
  encryptionSelect.addEventListener('change', generateQR);
  hiddenCheckbox.addEventListener('change', generateQR);

  // Generar QR inicial con valores por defecto
  generateQR();
});

// Estilos para las notificaciones
const style = document.createElement('style');
style.textContent = `
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

  .notification.warning {
    background-color: var(--warning-color);
  }

  .notification i {
    font-size: 1.2rem;
  }
`;
document.head.appendChild(style);