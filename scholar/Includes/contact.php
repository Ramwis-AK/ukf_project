<?php

require_once __DIR__ . '/../config/db_config.php';  // cesta podľa projektu uprav

class ContactFormHandler
{
    private $db;
    public $errors = [];
    public $successMessage = '';
    public $errorMessage = '';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Sanitácia vstupov
    private function sanitizeInput(string $input): string
    {
        return Database::sanitizeInput($input);
    }

    // Validácia vstupov
    private function validate(string $name, string $email, string $message): bool
    {
        $this->errors = [];
        //dĺžka 2+ a latinka + diakritika
        //v prípade núdze if (mb_strlen($name) < 2 || !preg_match('/^[\p{L}\s\'\-]+$/u', $name))
        if (strlen($name) < 2 || !preg_match('/^[a-zA-Zá-žÁ-Ž\s]+$/u', $name)) {
            $this->errors[] = "Meno musí obsahovať aspoň 2 písmená a iba písmená a medzery.";
        }
        //syntaktické overenie mailu (neskôr vyžaduje úpravu - overenie domény a pod.)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Neplatná emailová adresa.";
        }

        if (strlen($message) < 5) {
            $this->errors[] = "Správa musí obsahovať aspoň 5 znakov.";
        }

        return empty($this->errors);
    }

    // Spracovanie POST dát
    public function process(array $postData): void
    {
        $name = trim($this->sanitizeInput($postData['name'] ?? ''));
        $email = trim($this->sanitizeInput($postData['email'] ?? ''));
        $message = trim($this->sanitizeInput($postData['message'] ?? ''));

        if ($this->validate($name, $email, $message)) {
            $insertQuery = "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)";
            $params = [
                ':name' => $name,
                ':email' => $email,
                ':message' => $message
            ];

            $insertedId = $this->db->insert($insertQuery, $params);

            if ($insertedId !== false) {
                $this->successMessage = "Správa bola úspešne odoslaná. Ďakujeme!";
            } else {
                $this->errorMessage = "Nastala chyba pri odosielaní správy. Skúste to prosím neskôr.";
            }
        } else {
            $this->errorMessage = implode(' ', $this->errors);
        }
    }
}

// --- Použitie ---

$formHandler = new ContactFormHandler();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formHandler->process($_POST);
}

function get_form_action() {
    return "index.php"; // vráti na index.php
}

?>


<!-- Sekcia kontaktu -->
<div class="contact-us section" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 align-self-center"> <!-- Ľavý stĺpec, vertikálne vycentrovaný -->
                <div class="section-heading">
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
<div id="popup-modal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 20px; max-width: 400px; border-radius: 8px; box-shadow: 0 0 10px #000; text-align: center;">
        <p id="popup-message" style="margin-bottom: 20px;"></p>
        <button id="popup-close" style="padding: 10px 20px; cursor:pointer;">OK</button>
    </div>
</div>
<?php
// Ak správa nie je úspešná, skontrolujeme, či mám chybovú správu a použijeme ju.
$message = !empty($formHandler->successMessage) ? $formHandler->successMessage : (!empty($formHandler->errorMessage) ? $formHandler->errorMessage : '');

// Nastavíme typ správy podľa toho, či existuje úspešná správa.
// Ak je úspešná správa, `$messageType` bude "success", inak "error".
$messageType = !empty($formHandler->successMessage) ? 'success' : 'error';
?>

<?php if ($message): ?>
    <script>
        // Ak máme nejakú správu, prenesieme ju do JavaScriptu pomocou json_encode a zabezpečí, že reťazce
        var messageType = <?php echo json_encode($messageType); ?>;
        var messageText = <?php echo json_encode($message); ?>;
    </script>
<?php endif; ?>


<script>
    // Po načítaní celej stránky spustíme tento kód.
    document.addEventListener('DOMContentLoaded', function() {
        // Overíme, či je premenná messageText definovaná a nie je prázdna.
        if (typeof messageText !== 'undefined' && messageText) {
            // Získame element modálneho okna, kde sa zobrazí správa.
            const modal = document.getElementById('popup-modal');
            // Element, kam vložíme text správy.
            const messageEl = document.getElementById('popup-message');
            // Tlačidlo na zatvorenie modálneho okna.
            const closeBtn = document.getElementById('popup-close');

            // Vložíme text správy do elementu.
            messageEl.textContent = messageText;

            // Podľa typu správy nastavíme farbu textu:
            // zelená pre úspech, červená pre chybu.
            if(messageType === 'success') {
                messageEl.style.color = 'green';
            } else {
                messageEl.style.color = 'red';
            }

            // Zobrazíme modálne okno (nastavíme jeho zobrazenie na flex).
            modal.style.display = 'flex';

            // Pridáme poslucháča na tlačidlo zatvorenia, ktorý skryje modálne okno.
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
    });
</script>


<?php if (!empty($formHandler->successMessage) || !empty($formHandler->errorMessage)): ?>
    <script>
        // Ak máme buď úspešnú, alebo chybovú správu, po načítaní stránky
        // plynulo posunieme stránku úplne dole.
        window.addEventListener('load', function() {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        });
    </script>
<?php endif; ?>


