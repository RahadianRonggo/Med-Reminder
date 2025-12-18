// Simpan di: C:\xampp\htdocs\med-reminder\notification.js

// Alarm Sound
function playAlarmSound() {
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYHFWW24+WlaRsFOZHX8sx5LAUkd8fx5qJTEgtWsMn0zIRJDAg+o+P0xoIiDSaCzPLaiDoHE2W0nHQjBTCP1/HNfC0GJXbK8N+URA0PXbjr6KtaHQU9k93v05lZGQdEnOXwunIhBTGH0fPSgTkIGGS35+WmaRsCOZHY8sx4KwQjdMbv3Y9ADg5bs+nqp1YVDkCY4fK8cSQGMIvU89OBOQcXZLjp8LRXGwo8ldjxy30sBSV0xPDZjUARD1G26+OpWRsGPJXZ8cx8KwQjc8Xv34w+DQ5btOrpqVgVDj2U2fHMfCwFJXPE8N2PQBIPUbXr6atYGwU9lNjyy3wsBCJzxPDejz4NDlm06eqpWBsGPZPZ8cp4KwQjcsPv34w+DQ5btOnqp1gVDj2T2PHKfCsFJXPD8N6MPQ4OWbPp6qdYFQ49lNjxynsrBSN0w+/eiz4OD1m06uqnWRQOPZPZ8cp7KwQjc8Pv3os+Dg9YterqqVgVDj2T2PHKeisFI3PD796LPg4PWbXq6qdYFQ49k9jxynsrBSNzw/Deix4PD1m16uqnVhUOPZPY8cp7KwQjc8Pw3Yw+Dg9ZtOrpqVYVDT2T2PHKeisFI3PD8N6LPg4PWbXq6adWFQ09k9jxynorBCJzw/DeiTwOD1i16+qnVhUOPZPY8cp6KwUjc8Pw3oo+Dg9Ztero');
    audio.play().catch(() => console.log('Audio play failed'));
}

// Enhanced Notification
function showEnhancedNotification(title, body, icon = '💊') {
    if (!("Notification" in window)) {
        alert(title + ': ' + body);
        return;
    }

    if (Notification.permission === "granted") {
        const notification = new Notification(title, {
            body: body,
            icon: `data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75'>${icon}</text></svg>`,
            badge: `data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75'>💊</text></svg>`,
            requireInteraction: true,
            vibrate: [200, 100, 200], // Vibration pattern
            tag: 'medication-reminder',
            renotify: true
        });

        // Play sound
        playAlarmSound();

        // Close after 10 seconds
        setTimeout(() => notification.close(), 10000);

        notification.onclick = function() {
            window.focus();
            this.close();
        };
    }
}

// Check medications every minute
function checkMedicationSchedule(medications) {
    setInterval(() => {
        const now = new Date();
        const currentTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
        
        medications.forEach(med => {
            if (med.time === currentTime && !med.taken) {
                showEnhancedNotification(
                    "⏰ Waktunya Minum Obat!",
                    `${med.name} - ${med.dose}\n${med.notes || 'Jangan lupa minum obat Anda'}`,
                    '💊'
                );
                
                // Show in-app notification
                showInAppNotification(med);
            }
        });
    }, 60000); // Check every minute
}

// In-app notification
function showInAppNotification(med) {
    const notifDiv = document.createElement('div');
    notifDiv.className = 'in-app-notification';
    notifDiv.innerHTML = `
        <div class="notif-icon">⏰</div>
        <div class="notif-content">
            <h3>Waktunya Minum Obat!</h3>
            <p><strong>${med.name}</strong> - ${med.dose}</p>
            <small>${med.notes || ''}</small>
        </div>
        <button class="notif-close" onclick="this.parentElement.remove()">✕</button>
    `;
    
    document.body.appendChild(notifDiv);
    
    // Auto remove after 30 seconds
    setTimeout(() => {
        if (notifDiv.parentElement) {
            notifDiv.remove();
        }
    }, 30000);
}