<?php
/**
 * Contact section template
 *
 * @package Scholar
 */
?>

<!-- Sekcia kontaktu -->
<div class="contact-us section" id="contact">
    <div class="container"> <!-- Bootstrap kontajner pre centrovanie a padding -->
        <div class="row"> <!-- Bootstrap riadok pre rozloženie -->
            <div class="col-lg-6 align-self-center"> <!-- Ľavý stĺpec, vertikálne vycentrovaný -->
                <div class="section-heading"> <!-- Nadpis a info -->
                    <h6>kontaktujte nás</h6>
                    <h2>Neváhajte nás kedykoľvek kontaktovať</h2>
                    <p>
                        Odpovedáme najneskôr do troch pracovných dní.<br>
                        Ak to nestihneme, získavate zľavu 5%.<br>
                        <!-- Odkaz na podmienky v PDF, otvorí sa v novom okne s bezpečnostnými atribútmi -->
                        (<a href="dokumenty/podmienky.pdf" target="_blank" rel="noopener noreferrer">Bližšie podmienky tu</a>)
                    </p>
                    <div class="special-offer"> <!-- Špeciálna ponuka so zľavou -->
                        <span class="offer">zľava<br><em>20%</em></span>
                        <h6>Len do: <em>24 apríl 2036</em></h6>
                        <h4>Špeciálna Zľava <em>20%</em> </h4>
                        <!-- Link na PDF s ponukou -->
                        <a href="dokumenty/off_pdf.pdf" target="_blank" rel="noopener noreferrer">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6"> <!-- Pravý stĺpec s formulárom -->
                <div class="contact-us-content">
                    <!-- Formulár s ID 'contact-form', odosiela dáta POSTom na URL získanú funkciou get_form_action -->
                    <form id="contact-form" action="<?php echo htmlspecialchars(get_form_action()); ?>" method="post" novalidate>
                        <div class="row">
                            <div class="col-lg-12">
                                <fieldset>
                                    <!-- Textové pole pre meno, s autocomplete a required -->
                                    <input type="text" name="name" id="name" placeholder="Tvoje Meno..." autocomplete="on" required>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <!-- Emailové pole, povinné -->
                                    <input type="email" name="email" id="email" placeholder="Tvoj E-mail..." required>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <!-- Textarea pre správu, povinná -->
                                    <textarea name="message" id="message" placeholder="Tvoja správa..." required></textarea>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <!-- Tlačidlo na odoslanie formulára -->
                                    <button type="submit" id="form-submit" class="orange-button">Pošli správu teraz</button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Get form action URL
 * Táto funkcia určuje, kam sa formulár odošle.
 * Môžeš to v budúcnosti konfigurovať, napr. cez nastavenia alebo environmentálne premenné.
 */
function get_form_action() {
    return "index.php"; // Aktuálne sa odosiela na index.php
}
?>
