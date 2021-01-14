<?php declare(strict_types=1);


?>
<!DOCTYPE html>
<html lang="de">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker</title>
    <link rel="stylesheet" href="lib/css/style.css">
    <link rel="stylesheet" href="lib/css/nav.css">
       <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script> <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="lib/js/nav.js" type="text/javascript"></script>   
</head>
<body>
<header>
      <h1>Linker Socialising App</h1>
</header>
<?php if (! (isset($_SESSION['_user']))) : ?>
        <nav>
            <ul>
                <li><a href="register.php">REGISTER</a></li>
                <li><a href="login.php">LOGIN</a></li>
            </ul>
        </nav>
    <?php elseif (isset($_SESSION['_user'])) : ?>
        <a href="profile.php"><img src="<?=$_SESSION['_user']['avatar'] ?>" alt="Your avatar" class="navavatar"></a>
    <nav>
            <ul id="mainnav">
                <li><a href="parties.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/parties_sel.png" alt="Parties near me" class="navbutton active"></a></li>
                <li><a href="users.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/users.png" alt="People near me" class="navbutton"></a></li>
                <li><a href="messages.php"><img src="img/buttons/messages.png" alt="Messages" class="navbutton"></a></li>
                <li><a href="notifications.php"><img src="img/buttons/requests.png" alt="Notifications" class="navbutton"></a></li>
                <li><a href="logout.php"><img src="img/buttons/logout.png" alt="Log-out" class="navbutton"></a></li>
            </ul>
    </nav>
    <?php endif ; ?>

    <main>
        <p><strong>Datenschutzerklärung</strong></p>
<p><p>In dieser Datenschutzerklärung informieren wir Sie über die Verarbeitung personenbezogener Daten bei der 
    Nutzung dieser Website.</p><p><strong>Verantwortlicher</strong></p><p>Verantwortlich für die Datenverarbeitung 
        ist<br><em>Name, Adresse, Telefonnummer und E-Mail-Adresse des Websitebetreibers ergänzen</em></p>
        <p><strong>Datenschutzbeauftragter</strong></p><p><em>Die Angabe ist nur erforderlich bei 
            Pflicht zur Bestellung eines Datenschutzbeauftragten. Diese besteht in folgenden 
            Fällen:</em></p><ul><li><em>Im Unternehmen verarbeiten mindestens 10 Mitarbeiter regelmäßig 
                automatisiert Daten wie z. B. mit Computern.</em></li><li><em>Im Unternehmen verarbeiten 
                    Sie unabhängig von der Mitarbeiterzahl mindestens eine Kategorie folgender personenbezogener 
                    Daten: ethnische Herkunft, Rasse, politische Meinung, religiöse Überzeugung, 
                    Gewerkschaftszugehörigkeit, Gesundheit oder Sexualleben</em></li><li><em>Mittelpunkt 
                        der Geschäftstätigkeit ist die Übermittlung personenbezogener Daten wie sie z. B. 
                        eine Auskunftei, ein Adressverlag oder ein Markt- und Meinungsforschungsinstitut 
                        ausübt.</em></li></ul><p>Der Datenschutzbeauftragte ist unter der Adresse des 
                            Verantwortlichen zu erreichen. Bitte ergänzen Sie die Adresse bei der Kontaktaufnahme per 
                            Post mit dem Hinweis "Datenschutzbeauftragter". Per E-Mail erreichen Sie unseren 
                            Datenschutzbeauftragten über die folgende E-Mail-Adresse: <em>E-Mail-Adresse des 
                                Datenschutzbeauftragten einfügen</em></p><p><strong>Personenbezogene Daten

                                </strong></p><p>Personenbezogene Daten sind alle Informationen, die sich auf eine 
                                    identifizierte oder identifizierbare natürliche Person (im Folgenden "betroffene 
                                    Person") beziehen. Als identifizierbar wird eine natürliche Person angesehen, 
                                    die direkt oder indirekt, insbesondere mittels Zuordnung zu einer Kennung wie 
                                    einem Namen, zu einer Kennnummer, zu Standortdaten, zu einer Online-Kennung
                                     oder zu einem oder mehreren besonderen Merkmalen identifiziert werden kann, 
                                     die Ausdruck der physischen, physiologischen, genetischen, psychischen, 
                                     wirtschaftlichen, kulturellen oder sozialen Identität dieser natürlichen 
                                     Person sind.</p><p><strong>Daten beim Websiteaufruf</strong></p><p>Wenn Sie 
                                         diese Website nur nutzen, um sich zu informieren und keine Daten angeben, 
                                         dann verarbeiten wir nur die Daten, die zur Anzeige der Website auf dem
                                          von Ihnen verwendeten internetfähigen Gerät erforderlich sind. Das sind 
                                          insbesondere:</p><ul><li>IP-Adresse</li><li>Datum und Uhrzeit der Anfrage

        </li><li>jeweils übertragene Datenmenge</li><li>die Website, von der die
             Anforderung kommt</li><li>Browsertyp und Browserversion</li><li>Betriebssystem</li></ul><p>
                 Rechtsgrundlage für die Verarbeitung dieser Daten sind berechtigte Interessen gemäß Art.
                  6 Abs. 1 UAbs. 1 Buchstabe f) DSGVO, um die Darstellung der Website grundsätzlich zu
                   ermöglichen.</p><p>Darüber hinaus können Sie verschiedene Leistungen auf der Website 
                       nutzen, bei der weitere personenbezogene und nicht personenbezogene Daten verarbeitet
                        werden.</p><p><strong>Ihre Rechte</strong></p><p>Als betroffene Person haben Sie 
                            folgende Rechte:</p><ul><li>Sie haben ein Auskunftsrecht bezüglich der Sie
                                 betreffenden personenbezogenen Daten, die der Verantwortliche verarbeitet
                                  (Art. 15 DSGVO),</li><li>Sie haben das Recht auf Berichtigung der Sie 
                                      betreffenden Daten, wenn diese unrichtig oder unvollständig gespeichert 
                                      werden (Art. 16 DSGVO),</li><li>Sie haben das Recht auf Löschung 
                                          (Art. 17 DSGVO),</li><li>Sie haben das Recht, die Einschränkung 
                                              der Verarbeitung Ihrer personenbezogenen Daten zu verlangen 
                                              (Art. 18 DSGVO),</li><li>Sie haben das Recht auf Datenübertragbarkeit 
                                                  (Art. 20 DSGVO),</li><li>Sie haben ein Widerspruchsrecht gegen die 
                                                      Verarbeitung Sie betreffender personenbezogener Daten 
                                                      (Art. 21 DSGVO),</li><li>Sie haben das Recht nicht einer 
                                                          ausschließlich auf einer automatisierten Verarbeitung – 
                                                          einschließlich Profiling – beruhenden Entscheidung 
                                                          unterworfen zu werden, die Ihnen gegenüber rechtliche 
                                                          Wirkung entfaltet oder sie in ähnlicher Weise erheblich 
                                                          beeinträchtigt (Art. 22 DSGVO),</li><li>Sie haben das Recht, 
                                                              sich bei einem vermuteten Verstoß gegen das Datenschutzrecht
                                                               bei der zuständigen Aufsichtsbehörde zu beschweren (Art. 77 DSGVO). 
                                                               Zuständig ist die Aufsichtsbehörde an Ihrem üblichen Aufenthaltsort,
                                                                Arbeitsplatz oder am Ort des 
                                                                vermuteten Verstoßes.</li></ul><p><br></p></p>
<p>P.S. die Form für ausfühlen ist nicht funktionfähig. Wann ausgefühlt es schickt Daten an den Server aber die werden nicht bearbeitet.
    Der Gründ dafür ist, weil dies nur eine Test Seite ist.
</p>


<p>Quelle: <a href="https://www.anwalt.de/vorlage/muster-datenschutzerklaerung.php" rel="nofollow">Muster-Datenschutzerklärung von anwalt.de</a></p>
    </main>
    <footer>
        <ul>
            <li><a href="privacypolicy.php">Privacy Policy</a></li>
            <li><a href="agb.php">AGB</a></li>
        </ul>
        <p>
            Linker Version 0.001
        </p>
    </footer>
</body>
</html>