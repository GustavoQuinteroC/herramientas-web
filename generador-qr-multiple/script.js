document.addEventListener('DOMContentLoaded', function() {
  const qrCode = document.getElementById('qr-code');
  const generateBtn = document.getElementById('generate-btn');
  const printBtn = document.getElementById('print-btn');
  const downloadBtn = document.getElementById('download-btn');
  const loadingOverlay = document.getElementById('loading');

  // Tab functionality
  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const tabId = btn.getAttribute('data-tab');

      // Remove active class from all tabs and contents
      tabBtns.forEach(b => b.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));

      // Add active class to clicked tab and corresponding content
      btn.classList.add('active');
      document.getElementById(`${tabId}-tab`).classList.add('active');

      // Generate QR for the new tab
      generateQR();
    });
  });

  // WiFi specific elements
  const wifiSsidInput = document.getElementById('wifi-ssid');
  const wifiPasswordInput = document.getElementById('wifi-password');
  const wifiEncryptionSelect = document.getElementById('wifi-encryption');
  const wifiHiddenCheckbox = document.getElementById('wifi-hidden');
  const wifiTogglePasswordBtn = document.getElementById('wifi-toggle-password');

  // URL specific elements
  const urlInput = document.getElementById('url-input');

  // Text specific elements
  const textInput = document.getElementById('text-input');

  // vCard specific elements
  const vcardTypeSelect = document.getElementById('vcard-type');
  const vcardNameInput = document.getElementById('vcard-name');
  const vcardOrgInput = document.getElementById('vcard-org');
  const vcardTitleInput = document.getElementById('vcard-title');
  const vcardPhoneInput = document.getElementById('vcard-phone');
  const vcardEmailInput = document.getElementById('vcard-email');
  const vcardWebInput = document.getElementById('vcard-web');
  const vcardAddressInput = document.getElementById('vcard-address');

  // Event specific elements
  const eventTitleInput = document.getElementById('event-title');
  const eventStartInput = document.getElementById('event-start');
  const eventEndInput = document.getElementById('event-end');
  const eventLocationInput = document.getElementById('event-location');
  const eventDescriptionInput = document.getElementById('event-description');

  // WhatsApp specific elements
  const whatsappNumberInput = document.getElementById('whatsapp-number');
  const whatsappMessageInput = document.getElementById('whatsapp-message');

  // Email specific elements
  const emailToInput = document.getElementById('email-to');
  const emailSubjectInput = document.getElementById('email-subject');
  const emailBodyInput = document.getElementById('email-body');

  // SMS specific elements
  const smsNumberInput = document.getElementById('sms-number');
  const smsMessageInput = document.getElementById('sms-message');

  // Phone specific elements
  const phoneNumberInput = document.getElementById('phone-number');

  // Location specific elements
  const locationLatitudeInput = document.getElementById('location-latitude');
  const locationLongitudeInput = document.getElementById('location-longitude');
  const locationQueryInput = document.getElementById('location-query');
  const locationSearchBtn = document.getElementById('location-search');

  // Function to generate the QR code based on active tab
  function generateQR() {
    // Show loading indicator
    loadingOverlay.classList.add('active');

    // Get active tab
    const activeTab = document.querySelector('.tab-content.active').id;
    let qrData = '';

    switch(activeTab) {
      case 'wifi-tab':
        qrData = generateWiFiQR();
        break;
      case 'url-tab':
        qrData = generateURLQR();
        break;
      case 'text-tab':
        qrData = generateTextQR();
        break;
      case 'vcard-tab':
        qrData = generateVCardQR();
        break;
      case 'event-tab':
        qrData = generateEventQR();
        break;
      case 'whatsapp-tab':
        qrData = generateWhatsAppQR();
        break;
      case 'email-tab':
        qrData = generateEmailQR();
        break;
      case 'sms-tab':
        qrData = generateSMSQR();
        break;
      case 'phone-tab':
        qrData = generatePhoneQR();
        break;
      case 'location-tab':
        qrData = generateLocationQR();
        break;
    }

    // Update QR code image
    qrCode.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qrData)}`;

    // Hide loading indicator after a short delay
    setTimeout(() => {
      loadingOverlay.classList.remove('active');
      showNotification('Código QR generado correctamente', 'success');
    }, 500);
  }

  // Function to generate WiFi QR data
  function generateWiFiQR() {
    const ssid = wifiSsidInput.value.trim();
    const password = wifiPasswordInput.value;
    const encryption = wifiEncryptionSelect.value;
    const hidden = wifiHiddenCheckbox.checked;

    if (!ssid) {
      showNotification('Por favor, introduce un nombre de red (SSID)', 'warning');
      loadingOverlay.classList.remove('active');
      return 'WIFI:T:WPA;S:;P:;;';
    }

    let wifiString = `WIFI:T:${encryption};S:${ssid};`;

    if (encryption !== 'nopass') {
      wifiString += `P:${password};`;
    }

    if (hidden) {
      wifiString += 'H:true;';
    }

    wifiString += ';';

    return wifiString;
  }

  // Function to generate URL QR data
  function generateURLQR() {
    const url = urlInput.value.trim();

    if (!url) {
      showNotification('Por favor, introduce una URL válida', 'warning');
      loadingOverlay.classList.remove('active');
      return 'https://';
    }

    // Ensure URL has protocol
    let finalUrl = url;
    if (!url.startsWith('http://') && !url.startsWith('https://')) {
      finalUrl = 'https://' + url;
    }

    return finalUrl;
  }

  // Function to generate text QR data
  function generateTextQR() {
    const text = textInput.value.trim();

    if (!text) {
      showNotification('Por favor, introduce un texto', 'warning');
      loadingOverlay.classList.remove('active');
      return 'Texto';
    }

    return text;
  }

  // Function to generate vCard QR data
  function generateVCardQR() {
    const name = vcardNameInput.value.trim();
    const org = vcardOrgInput.value.trim();
    const title = vcardTitleInput.value.trim();
    const phone = vcardPhoneInput.value.trim();
    const email = vcardEmailInput.value.trim();
    const web = vcardWebInput.value.trim();
    const address = vcardAddressInput.value.trim();

    if (!name) {
      showNotification('Por favor, introduce un nombre', 'warning');
      loadingOverlay.classList.remove('active');
      return 'BEGIN:VCARD\nVERSION:3.0\nFN:Nombre\nEND:VCARD';
    }

    let vcard = 'BEGIN:VCARD\nVERSION:3.0\nFN:' + name + '\n';

    if (org) vcard += 'ORG:' + org + '\n';
    if (title) vcard += 'TITLE:' + title + '\n';
    if (phone) vcard += 'TEL:' + phone + '\n';
    if (email) vcard += 'EMAIL:' + email + '\n';
    if (web) vcard += 'URL:' + web + '\n';
    if (address) vcard += 'ADR:;;' + address + ';;;;\n';

    vcard += 'END:VCARD';

    return vcard;
  }

  // Function to generate event QR data
  function generateEventQR() {
    const title = eventTitleInput.value.trim();
    const start = eventStartInput.value;
    const end = eventEndInput.value;
    const location = eventLocationInput.value.trim();
    const description = eventDescriptionInput.value.trim();

    if (!title || !start || !end) {
      showNotification('Por favor, completa los campos obligatorios del evento', 'warning');
      loadingOverlay.classList.remove('active');
      return 'BEGIN:VEVENT\nSUMMARY:Evento\nDTSTART:20230101T000000Z\nDTEND:20230101T010000Z\nEND:VEVENT';
    }

    // Format dates for iCalendar
    const formatDate = (dateString) => {
      const date = new Date(dateString);
      return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    };

    let event = 'BEGIN:VEVENT\n';
    event += 'SUMMARY:' + title + '\n';
    event += 'DTSTART:' + formatDate(start) + '\n';
    event += 'DTEND:' + formatDate(end) + '\n';

    if (location) event += 'LOCATION:' + location + '\n';
    if (description) event += 'DESCRIPTION:' + description + '\n';

    event += 'END:VEVENT';

    return event;
  }

  // Function to generate WhatsApp QR data
  function generateWhatsAppQR() {
    const number = whatsappNumberInput.value.trim();
    const message = whatsappMessageInput.value.trim();

    if (!number) {
      showNotification('Por favor, introduce un número de teléfono', 'warning');
      loadingOverlay.classList.remove('active');
      return 'https://wa.me/';
    }

    // Clean phone number (remove +, spaces, etc.)
    const cleanNumber = number.replace(/[^\d]/g, '');

    let whatsappUrl = 'https://wa.me/' + cleanNumber;

    if (message) {
      whatsappUrl += '?text=' + encodeURIComponent(message);
    }

    return whatsappUrl;
  }

  // Function to generate email QR data
  function generateEmailQR() {
    const to = emailToInput.value.trim();
    const subject = emailSubjectInput.value.trim();
    const body = emailBodyInput.value.trim();

    if (!to) {
      showNotification('Por favor, introduce un email destinatario', 'warning');
      loadingOverlay.classList.remove('active');
      return 'mailto:';
    }

    let emailUrl = 'mailto:' + to;

    const params = [];
    if (subject) params.push('subject=' + encodeURIComponent(subject));
    if (body) params.push('body=' + encodeURIComponent(body));

    if (params.length > 0) {
      emailUrl += '?' + params.join('&');
    }

    return emailUrl;
  }

  // Function to generate SMS QR data
  function generateSMSQR() {
    const number = smsNumberInput.value.trim();
    const message = smsMessageInput.value.trim();

    if (!number) {
      showNotification('Por favor, introduce un número de teléfono', 'warning');
      loadingOverlay.classList.remove('active');
      return 'sms:';
    }

    // Clean phone number (remove +, spaces, etc.)
    const cleanNumber = number.replace(/[^\d]/g, '');

    let smsUrl = 'sms:' + cleanNumber;

    if (message) {
      smsUrl += '?body=' + encodeURIComponent(message);
    }

    return smsUrl;
  }

  // Function to generate phone QR data
  function generatePhoneQR() {
    const number = phoneNumberInput.value.trim();

    if (!number) {
      showNotification('Por favor, introduce un número de teléfono', 'warning');
      loadingOverlay.classList.remove('active');
      return 'tel:';
    }

    // Clean phone number (remove +, spaces, etc.)
    const cleanNumber = number.replace(/[^\d]/g, '');

    return 'tel:' + cleanNumber;
  }

  // Function to generate location QR data
  function generateLocationQR() {
    const latitude = locationLatitudeInput.value.trim();
    const longitude = locationLongitudeInput.value.trim();

    if (!latitude || !longitude) {
      showNotification('Por favor, introduce latitud y longitud', 'warning');
      loadingOverlay.classList.remove('active');
      return 'geo:0,0';
    }

    return 'geo:' + latitude + ',' + longitude;
  }

  // Function to show notifications
  function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
      <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
      <span>${message}</span>
    `;

    // Add to DOM
    document.body.appendChild(notification);

    // Show with animation
    setTimeout(() => {
      notification.classList.add('show');
    }, 10);

    // Hide after 3 seconds
    setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => {
        document.body.removeChild(notification);
      }, 300);
    }, 3000);
  }

  // Function to print the QR code
  function printQR() {
    window.print();
  }

  // Function to download the QR code
  function downloadQR() {
    const link = document.createElement('a');
    link.href = qrCode.src;

    // Get filename based on active tab
    const activeTab = document.querySelector('.tab-content.active').id;
    let filename = 'qr-code';

    switch(activeTab) {
      case 'wifi-tab':
        filename = `wifi-qr-${wifiSsidInput.value || 'network'}`;
        break;
      case 'url-tab':
        filename = `url-qr-${urlInput.value.replace(/[^a-z0-9]/gi, '_').toLowerCase() || 'link'}`;
        break;
      case 'text-tab':
        filename = `text-qr-${textInput.value.substring(0, 20).replace(/[^a-z0-9]/gi, '_').toLowerCase() || 'text'}`;
        break;
      case 'vcard-tab':
        filename = `vcard-qr-${vcardNameInput.value.replace(/[^a-z0-9]/gi, '_').toLowerCase() || 'contact'}`;
        break;
      case 'event-tab':
        filename = `event-qr-${eventTitleInput.value.replace(/[^a-z0-9]/gi, '_').toLowerCase() || 'event'}`;
        break;
      case 'whatsapp-tab':
        filename = `whatsapp-qr-${whatsappNumberInput.value.replace(/[^a-z0-9]/gi, '_').toLowerCase() || 'contact'}`;
        break;
      case 'email-tab':
        filename = `email-qr-${emailToInput.value.replace(/[^a-z0-9]/gi, '_').toLowerCase() || 'email'}`;
        break;
      case 'sms-tab':
        filename = `sms-qr-${smsNumberInput.value.replace(/[^a-z0-9]/gi, '_').toLowerCase() || 'message'}`;
        break;
      case 'phone-tab':
        filename = `phone-qr-${phoneNumberInput.value.replace(/[^a-z0-9]/gi, '_').toLowerCase() || 'phone'}`;
        break;
      case 'location-tab':
        filename = `location-qr-${locationLatitudeInput.value}_${locationLongitudeInput.value || 'location'}`;
        break;
    }

    link.download = `${filename}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showNotification('Código QR descargado', 'success');
  }

  // Function to toggle password visibility
  function togglePassword(inputId, buttonId) {
    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);

    // Change icon
    const icon = button.querySelector('i');
    icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
  }

  // Function to search for location coordinates
  function searchLocation() {
    const query = locationQueryInput.value.trim();

    if (!query) {
      showNotification('Por favor, introduce una dirección para buscar', 'warning');
      return;
    }

    // Show loading
    loadingOverlay.classList.add('active');

    // Use Nominatim API to get coordinates
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        if (data && data.length > 0) {
          const { lat, lon } = data[0];
          locationLatitudeInput.value = lat;
          locationLongitudeInput.value = lon;
          generateQR();
        } else {
          showNotification('No se encontraron coordenadas para esta dirección', 'warning');
          loadingOverlay.classList.remove('active');
        }
      })
      .catch(error => {
        console.error('Error al buscar ubicación:', error);
        showNotification('Error al buscar la ubicación', 'warning');
        loadingOverlay.classList.remove('active');
      });
  }

  // Event listeners
  generateBtn.addEventListener('click', generateQR);
  printBtn.addEventListener('click', printQR);
  downloadBtn.addEventListener('click', downloadQR);

  // WiFi event listeners
  wifiTogglePasswordBtn.addEventListener('click', () => togglePassword('wifi-password', 'wifi-toggle-password'));
  wifiSsidInput.addEventListener('input', generateQR);
  wifiPasswordInput.addEventListener('input', generateQR);
  wifiEncryptionSelect.addEventListener('change', generateQR);
  wifiHiddenCheckbox.addEventListener('change', generateQR);

  // URL event listeners
  urlInput.addEventListener('input', generateQR);

  // Text event listeners
  textInput.addEventListener('input', generateQR);

  // vCard event listeners
  vcardTypeSelect.addEventListener('change', generateQR);
  vcardNameInput.addEventListener('input', generateQR);
  vcardOrgInput.addEventListener('input', generateQR);
  vcardTitleInput.addEventListener('input', generateQR);
  vcardPhoneInput.addEventListener('input', generateQR);
  vcardEmailInput.addEventListener('input', generateQR);
  vcardWebInput.addEventListener('input', generateQR);
  vcardAddressInput.addEventListener('input', generateQR);

  // Event event listeners
  eventTitleInput.addEventListener('input', generateQR);
  eventStartInput.addEventListener('change', generateQR);
  eventEndInput.addEventListener('change', generateQR);
  eventLocationInput.addEventListener('input', generateQR);
  eventDescriptionInput.addEventListener('input', generateQR);

  // WhatsApp event listeners
  whatsappNumberInput.addEventListener('input', generateQR);
  whatsappMessageInput.addEventListener('input', generateQR);

  // Email event listeners
  emailToInput.addEventListener('input', generateQR);
  emailSubjectInput.addEventListener('input', generateQR);
  emailBodyInput.addEventListener('input', generateQR);

  // SMS event listeners
  smsNumberInput.addEventListener('input', generateQR);
  smsMessageInput.addEventListener('input', generateQR);

  // Phone event listeners
  phoneNumberInput.addEventListener('input', generateQR);

  // Location event listeners
  locationLatitudeInput.addEventListener('input', generateQR);
  locationLongitudeInput.addEventListener('input', generateQR);
  locationSearchBtn.addEventListener('click', searchLocation);

  // Generate initial QR
  generateQR();
});

// Styles for notifications
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