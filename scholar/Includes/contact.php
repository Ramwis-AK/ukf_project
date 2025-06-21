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

        if (strlen($name) < 2 || !preg_match('/^[a-zA-Zá-žÁ-Ž\s]+$/u', $name)) {
            $this->errors[] = "Meno musí obsahovať aspoň 2 písmená a iba písmená a medzery.";
        }

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
$message = !empty($formHandler->successMessage) ? $formHandler->successMessage : (!empty($formHandler->errorMessage) ? $formHandler->errorMessage : '');
$messageType = !empty($formHandler->successMessage) ? 'success' : 'error';
?>

<?php if ($message): ?>
    <script>
        var messageType = "<?php echo $messageType; ?>";
        var messageText = "<?php echo addslashes($message); ?>";
    </script>
<?php endif; ?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof messageText !== 'undefined' && messageText) {
            const modal = document.getElementById('popup-modal');
            const messageEl = document.getElementById('popup-message');
            const closeBtn = document.getElementById('popup-close');

            messageEl.textContent = messageText;
            if(messageType === 'success') {
                messageEl.style.color = 'green';
            } else {
                messageEl.style.color = 'red';
            }
            modal.style.display = 'flex';

            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
    });
</script>


<?php if (!empty($formHandler->successMessage) || !empty($formHandler->errorMessage)): ?>
    <script>
        window.addEventListener('load', function() {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        });
    </script>
<?php endif; ?>

