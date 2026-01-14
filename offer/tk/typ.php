<?php
session_start();

$redirectUrl = $_SESSION['redirectUrl'] ?? '';
$nameMarca = $_SESSION['nameMarca'] ?? '';

?><!DOCTYPE html>

<html lang="de">
<head>
<meta charset="utf-8"/>
<meta content="ie=edge" http-equiv="x-ua-compatible"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<title><?php echo htmlspecialchars($nameMarca); ?></title>
<meta content="Thank you page with countdown and deposit reminder." name="description"/>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<style>
      body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0f0f13 0%, #1a1a2e 100%);
        background-attachment: fixed;
        min-height: 100vh;
      }

      .progress-bar {
        transition: width 0.5s ease-in-out;
      }

      .countdown-pulse {
        animation: pulse 2s infinite;
      }

      @keyframes pulse {
        0% {
          transform: scale(1);
        }

        50% {
          transform: scale(1.05);
        }

        100% {
          transform: scale(1);
        }
      }

      .fade-in {
        animation: fadeIn 1s ease-in;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
        }

        to {
          opacity: 1;
        }
      }
    </style>
</head>
<body class="min-h-screen flex flex-col">
<!-- Main content -->
<main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
<div class="max-w-md w-full bg-white rounded-xl shadow-lg overflow-hidden fade-in">
<!-- Confirmation header -->
<div class="bg-gradient-to-r from-blue-900 via-blue-700 to-blue-500 px-6 py-8 text-center">
<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20 mb-4">
<i class="fas fa-check text-white text-xl"></i>
</div>
<h1 class="text-2xl font-bold text-white">Ihre Anfrage wurde erfolgreich versendet!</h1>
<p class="mt-2 text-blue-200">Vielen Dank für Ihre Anfrage.</p>
</div>
<!-- Countdown section -->
<div class="px-6 py-8">
<div class="text-center mb-6">
<p class="text-gray-600 mb-4">Einer unserer erfahrenen Geschäftsberater wird Sie in Kürze während des Onboarding-Prozesses begleiten:</p>
<div class="countdown-pulse inline-flex flex-col items-center justify-center mb-6">
<div class="relative">
<div class="absolute inset-0 bg-blue-100 rounded-full opacity-20 -z-10"></div>
<span class="text-5xl font-bold text-blue-600" id="countdown-value">5:00</span>
</div>
<span class="text-gray-500 mt-2" id="countdown-label">Minuten</span>
</div>
<div class="w-full bg-gray-200 rounded-full h-2.5 mb-6">
<div class="progress-bar bg-blue-600 h-2.5 rounded-full" id="progress-inner" style="width: 0%"></div>
</div>
<p class="text-gray-500 italic mb-6" id="end-msg">Wir bereiten alles für dich vor...</p>
</div>
<!-- Deposit reminder -->
<div class="bg-blue-50 rounded-lg p-4 mb-6">
<div class="flex items-start">
<div class="flex-shrink-0">
<i class="fas fa-info-circle text-blue-500 mt-1"></i>
</div>
<div class="ml-3">
<p class="text-sm text-gray-700">Für die Eröffnung eines neuen Kontos, das noch genehmigt werden muss, ist eine Mindesteinzahlung erforderlich von<span class="font-bold text-blue-600">250 €</span>erforderlich.</p>
</div>
</div>
</div>
<!-- Next steps -->
<div class="bg-gray-50 rounded-lg p-4 mb-6">
<h3 class="font-medium text-gray-800 mb-2">Nächste Schritte:</h3>
<ul class="space-y-3 text-sm text-gray-600">
<li class="flex items-start">
<i class="fas fa-user-cog text-blue-500 mt-0.5 mr-2"></i>
<span>Konto-Einrichtung abschließen</span>
</li>
<li class="flex items-start">
<i class="fas fa-money-bill-wave text-blue-500 mt-0.5 mr-2"></i>
<span>Tätigen Sie Ihre erste Einzahlung</span>
</li>
<li class="flex items-start">
<i class="fas fa-rocket text-blue-500 mt-0.5 mr-2"></i>
<span>Beginnen Sie mit der Nutzung Ihres Kontos</span>
</li>
</ul>
</div>
<!-- Account button -->
<button class="w-full flex items-center justify-center px-8 py-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" onclick="document.location.href='<?php echo htmlspecialchars($redirectUrl); ?>'">Gehen Sie zum Bereich "Mein Konto"<i class="fas fa-arrow-right ml-2"></i>
</button>
</div>
</div>
</main>
<!-- Footer -->
<footer class="bg-white py-4 px-6 border-t">
<div class="max-w-4xl mx-auto text-center text-gray-500 text-sm">
<p class="mt-1">© <?php echo date("Y"); ?> <?php echo htmlspecialchars($nameMarca); ?>. Alle Rechte vorbehalten.</p>
</div>
</footer>
<script>
      // Countdown timer
      let timeLeft = 300; // 5 minutes in seconds
      const countdownElement = document.getElementById('countdown-value');
      const progressElement = document.getElementById('progress-inner');
      const endMsgElement = document.getElementById('end-msg');
      const countdown = setInterval(() => {
        timeLeft--;
        // Update display
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        // Update progress bar
        const progressPercentage = ((300 - timeLeft) / 300) * 100;
        progressElement.style.width = `${progressPercentage}%`;
        // Change color when less than 1 minute
        if (timeLeft < 60) {
          countdownElement.classList.remove('text-blue-600');
          countdownElement.classList.add('text-red-500');
          progressElement.classList.remove('bg-blue-600');
          progressElement.classList.add('bg-red-500');
        }
        // End countdown
        if (timeLeft <= 0) {
          clearInterval(countdown);
          countdownElement.textContent = "0:00";
          endMsgElement.textContent = "Your account is ready!";
          endMsgElement.classList.remove('text-gray-500');
          endMsgElement.classList.add('text-green-600', 'font-medium');
        }
      }, 1000);
    </script>
</body>
</html>