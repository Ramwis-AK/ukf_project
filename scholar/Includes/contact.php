<?php
/**
 * Contact section template
 *
 * @package Scholar
 */
?>

    <div class="contact-us section" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="section-heading">
                        <h6>kontaktujte nás</h6>
                        <h2>Neváhajte nás kedykoľvek kontaktovať</h2>
                        <p>Odpovedáme najneskôr do troch pracovných dní.<br>
                            Ak to nestihneme, získavate zľavu 5%.<br>
                            (<a href="dokumenty/podmienky.pdf" target="_blank" rel="noopener noreferrer">Bližšie podmienky tu</a>)
                        </p>
                        <div class="special-offer">
                            <span class="offer">zľava<br><em>20%</em></span>
                            <h6>Len do: <em>24 apríl 2036</em></h6>
                            <h4>Špeciálna Zľava <em>20%</em> </h4>
                            <a href="off_pdf.php" target="_blank" rel="noopener noreferrer">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-us-content">
                        <form id="contact-form" action="<?php echo get_form_action(); ?>" method="post">
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset>
                                        <input type="name" name="name" id="name" placeholder="Tvoje Meno..." autocomplete="on" required>
                                    </fieldset>
                                </div>
                                <div class="col-lg-12">
                                    <fieldset>
                                        <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Tvoj E-mail..." required="">
                                    </fieldset>
                                </div>
                                <div class="col-lg-12">
                                    <fieldset>
                                        <textarea name="message" id="message" placeholder="Tvoja správa..."></textarea>
                                    </fieldset>
                                </div>
                                <div class="col-lg-12">
                                    <fieldset>
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
 * This can be overridden or configured in the future
 */
function get_form_action() {
    // You could pull this from configuration or settings
    return "process-contact.php";
}
?>