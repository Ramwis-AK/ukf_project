<?php
/**
 * Preloader component with animation and JS to hide after load
 * Zobrazuje sa pred načítaním stránky a zmizne po načítaní.
 */
?>

<style>
    /* Celostránkový overlay s tmavým pozadím a zarovnaním obsahu do stredu */
    #js-preloader {
        position: fixed;
        top: 0; left: 0;
        width: 100vw;
        height: 100vh;
        background: #111;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Nad všetkým */
        transition: opacity 0.5s ease, visibility 0.5s ease; /* Efekt zmiznutia */
    }

    /* Trieda pridávaná po načítaní – skrýva preloader */
    #js-preloader.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    /* Kontajner pre loader – horizontálne zarovnané prvky */
    .preloader-inner {
        display: flex;
        align-items: center;
    }

    /* Oranžová bodka pulzujúca (hlavný loader bod) */
    .dot {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        background: #ff6600;
        border-radius: 50%;
        animation: pulse 1.2s infinite ease-in-out;
    }

    /* Tri menšie bodky s bouncovaním – sekvenčné animácie */
    .dots span {
        width: 12px;
        height: 12px;
        margin: 0 4px;
        background: #ff6600;
        border-radius: 50%;
        animation: bounce 1.4s infinite ease-in-out;
    }

    /* Postupné oneskorenia pre efekt sekvenčného skákania */
    .dots span:nth-child(1) {
        animation-delay: 0s;
    }
    .dots span:nth-child(2) {
        animation-delay: 0.2s;
    }
    .dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    /* Pulzujúci efekt pre hlavnú bodku */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.4;
            transform: scale(0.7);
        }
    }

    /* Skákací efekt pre tri malé bodky */
    @keyframes bounce {
        0%, 80%, 100% {
            transform: translateY(0);
            opacity: 0.6;
        }
        40% {
            transform: translateY(-15px);
            opacity: 1;
        }
    }
</style>

<!-- HTML štruktúra preloadera: veľký oranžový bod a tri skákajúce bodky -->
<div id="js-preloader" class="js-preloader" role="progressbar" aria-label="Loading content">
    <div class="preloader-inner" aria-hidden="true">
        <span class="dot"></span>
        <div class="dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>

<script>
    // Po načítaní stránky sa pridá trieda .hidden pre plynulé zmiznutie
    window.addEventListener('load', function() {
        const preloader = document.getElementById('js-preloader');
        preloader.classList.add('hidden'); // CSS pre animované skrývanie

        // Po animácii pre istotu kompletne odstráni element z rozloženia
        setTimeout(() => preloader.style.display = 'none', 600);
    });
</script>
