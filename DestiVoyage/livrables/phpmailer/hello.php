<?php 
// session_start();
?>
<?php
include ("fonction.php");

if (isset($_SESSION["email"])) {
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<div class="alert alert-success">Mot de passe mis à jour avec succès.</div>';
    }
    $host = 'mysql-destivoyage.alwaysdata.net';
    $dbname = 'destivoyage_projetdwa';
    $username = '333374_kenzi';
    $password = 'projetdwa';
    $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $con = new PDO($string,$username,$password);

    $userId = $_SESSION["email"];

    $queryUserInfo = "SELECT * FROM user WHERE email = :userId";
    $stmtUserInfo = $con->prepare($queryUserInfo);
    $stmtUserInfo->bindParam(':userId', $userId, PDO::PARAM_STR);
    $stmtUserInfo->execute();
    $user = null;
    if ($stmtUserInfo->rowCount() > 0) {
    $user = $stmtUserInfo->fetch(PDO::FETCH_ASSOC);
   }
$queryRegisteredFlights = "SELECT * FROM favvol WHERE user_id = :userId";
$stmtRegisteredFlights = $con->prepare($queryRegisteredFlights);
$stmtRegisteredFlights->bindParam(':userId', $userId, PDO::PARAM_STR);
$stmtRegisteredFlights->execute();
$registeredFlights = array();
if ($stmtRegisteredFlights->rowCount() > 0) {
    $registeredFlights = $stmtRegisteredFlights->fetchAll(PDO::FETCH_ASSOC);
}


}else{
    header("Location: connexion.php");
    echo "La session email n'est pas définie. ";
    var_dump($_SESSION);

}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DestiVoyage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleprofil.css">
    <link rel="stylesheet" href="assets/css/stylenav.css">
    <link rel="stylesheet" href="assets/css/stylefooter.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
    <?php
    session_start();
if (isset($_SESSION["email"])) {
    include("navlogin.php");
}else {
            header("Location: connexion.php");
        }
?>
   
    <main id="cont" class="container">
        <section class="nav-sidebar">
            <div class="user-info">
            <span id="colorful"><?php echo ($user['nom'] ?? ''); ?></span><br>
            <span id="colorful"><?php echo ($user['prenom'] ?? ''); ?></span>
            </div>
            <br>
            <a id="changePasswordBtn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="black" stroke-width="1.5" />
                    <path d="M13.7655 2.152C13.3985 2 12.9325 2 12.0005 2C11.0685 2 10.6025 2 10.2355 2.152C9.99263 2.25251 9.772 2.3999 9.58617 2.58572C9.40035 2.77155 9.25296 2.99218 9.15245 3.235C9.06045 3.458 9.02345 3.719 9.00945 4.098C9.00322 4.37199 8.92745 4.6399 8.78926 4.87657C8.65107 5.11324 8.455 5.31091 8.21945 5.451C7.98035 5.58504 7.71109 5.6561 7.43698 5.6575C7.16288 5.6589 6.89291 5.59059 6.65245 5.459C6.31645 5.281 6.07345 5.183 5.83245 5.151C5.30677 5.08187 4.77515 5.22431 4.35445 5.547C4.04045 5.79 3.80645 6.193 3.34045 7C2.87445 7.807 2.64045 8.21 2.58945 8.605C2.55509 8.86545 2.57237 9.13012 2.64032 9.38389C2.70826 9.63767 2.82554 9.87556 2.98545 10.084C3.13345 10.276 3.34045 10.437 3.66145 10.639C4.13445 10.936 4.43845 11.442 4.43845 12C4.43845 12.558 4.13445 13.064 3.66145 13.36C3.34045 13.563 3.13245 13.724 2.98545 13.916C2.82554 14.1244 2.70826 14.3623 2.64032 14.6161C2.57237 14.8699 2.55509 15.1345 2.58945 15.395C2.64145 15.789 2.87445 16.193 3.33945 17C3.80645 17.807 4.03945 18.21 4.35445 18.453C4.56289 18.6129 4.80078 18.7302 5.05456 18.7981C5.30833 18.8661 5.573 18.8834 5.83345 18.849C6.07345 18.817 6.31645 18.719 6.65245 18.541C6.89291 18.4094 7.16288 18.3411 7.43698 18.3425C7.71109 18.3439 7.98035 18.415 8.21945 18.549C8.70245 18.829 8.98945 19.344 9.00945 19.902C9.02345 20.282 9.05945 20.542 9.15245 20.765C9.25296 21.0078 9.40035 21.2284 9.58617 21.4143C9.772 21.6001 9.99263 21.7475 10.2355 21.848C10.6025 22 11.0685 22 12.0005 22C12.9325 22 13.3985 22 13.7655 21.848C14.0083 21.7475 14.2289 21.6001 14.4147 21.4143C14.6006 21.2284 14.7479 21.0078 14.8484 20.765C14.9404 20.542 14.9775 20.282 14.9915 19.902C15.0115 19.344 15.2985 18.828 15.7815 18.549C16.0206 18.415 16.2898 18.3439 16.5639 18.3425C16.838 18.3411 17.108 18.4094 17.3484 18.541C17.6844 18.719 17.9274 18.817 18.1674 18.849C18.4279 18.8834 18.6926 18.8661 18.9463 18.7981C19.2001 18.7302 19.438 18.6129 19.6465 18.453C19.9615 18.211 20.1944 17.807 20.6604 17C21.1264 16.193 21.3604 15.79 21.4114 15.395C21.4458 15.1345 21.4285 14.8699 21.3606 14.6161C21.2926 14.3623 21.1754 14.1244 21.0154 13.916C20.8674 13.724 20.6605 13.563 20.3395 13.361C20.1052 13.2186 19.911 13.019 19.775 12.7809C19.6391 12.5428 19.566 12.2741 19.5625 12C19.5625 11.442 19.8665 10.936 20.3395 10.64C20.6605 10.437 20.8684 10.276 21.0154 10.084C21.1754 9.87556 21.2926 9.63767 21.3606 9.38389C21.4285 9.13012 21.4458 8.86545 21.4114 8.605C21.3594 8.211 21.1264 7.807 20.6614 7C20.1944 6.193 19.9615 5.79 19.6465 5.547C19.438 5.38709 19.2001 5.26981 18.9463 5.20187C18.6926 5.13392 18.4279 5.11664 18.1674 5.151C17.9274 5.183 17.6845 5.281 17.3475 5.459C17.1071 5.59042 16.8373 5.65862 16.5634 5.65722C16.2895 5.65582 16.0204 5.58486 15.7815 5.451C15.5459 5.31091 15.3498 5.11324 15.2116 4.87657C15.0734 4.6399 14.9977 4.37199 14.9915 4.098C14.9775 3.718 14.9414 3.458 14.8484 3.235C14.7479 2.99218 14.6006 2.77155 14.4147 2.58572C14.2289 2.3999 14.0083 2.25251 13.7655 2.152Z" stroke="black" stroke-width="1.5" />
                </svg> Changer Mot de Passe</a>
            <a id="volsBtn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <g clip-path="url(#clip0_439_138)">
                        <path d="M23.8469 5.43997C23.7959 5.11552 23.6474 4.81426 23.4211 4.57614C23.1949 4.33801 22.9017 4.17427 22.5803 4.10663L18.6669 3.24663C18.2473 3.15511 17.8129 3.15477 17.3931 3.24563C16.9733 3.33649 16.5779 3.51644 16.2336 3.7733L4.66694 12.2L1.42694 12.0666C1.16648 12.0573 0.909707 12.1303 0.693088 12.2752C0.47647 12.4201 0.311019 12.6296 0.220236 12.8739C0.129454 13.1182 0.117955 13.3849 0.187372 13.6361C0.25679 13.8873 0.403595 14.1103 0.606936 14.2733L3.94027 16.8933C4.34027 17.38 4.60694 17.2866 11.2269 13.68L11.8469 19.96C11.8573 20.1313 11.916 20.2961 12.0163 20.4354C12.1166 20.5747 12.2544 20.6826 12.4136 20.7466C12.5282 20.7919 12.6504 20.8145 12.7736 20.8133C13.0215 20.8057 13.2584 20.7086 13.4403 20.54L15.0336 19.0866C15.1923 18.9396 15.2997 18.7457 15.3403 18.5333L16.8003 10.6C19.1803 9.26663 21.4336 8.0133 23.0403 7.09997C23.3277 6.93828 23.559 6.69271 23.7031 6.39607C23.8473 6.09944 23.8974 5.76588 23.8469 5.43997ZM22.3803 5.93997C20.7136 6.88663 18.3803 8.2133 15.8736 9.58663L15.6003 9.73997L14.0469 18.1866L13.0669 19.08L12.3336 11.5466L11.4469 12C6.66694 14.6666 5.0736 15.44 4.52694 15.68L1.66027 13.4066L5.04694 13.5533L17.0469 4.84663C17.2356 4.70562 17.4518 4.606 17.6816 4.55431C17.9113 4.50261 18.1494 4.5 18.3803 4.54663L22.2803 5.38663C22.3404 5.39753 22.3954 5.42734 22.4374 5.47171C22.4794 5.51608 22.5061 5.57269 22.5136 5.6333C22.5273 5.69196 22.5218 5.75348 22.4977 5.80872C22.4737 5.86397 22.4325 5.91 22.3803 5.93997Z" fill="black" />
                        <path d="M4.66673 8.36017L7.04006 9.02684L8.13339 8.2335L5.46673 7.46017L6.66673 6.72684L10.3134 6.62017L11.8467 5.50684L6.66673 5.66684C6.50342 5.658 6.34112 5.69742 6.20006 5.78017L4.44673 6.80017C4.30177 6.88604 4.18548 7.0129 4.11251 7.16476C4.03954 7.31662 4.01315 7.48668 4.03668 7.65351C4.06021 7.82034 4.1326 7.97647 4.24472 8.10223C4.35684 8.22798 4.50368 8.31773 4.66673 8.36017Z" fill="black" />
                    </g>
                    <defs>
                        <clipPath id="clip0_439_138">
                            <rect width="24" height="24" fill="white" />
                        </clipPath>
                    </defs>
                </svg> Vols</a>
            <div id="text-container" class="text-container"></div>
        </section>

        <section class="main-section">
            <div class="user-description">
                <form action="update_profil.php" method="post" enctype="multipart/form-data">
                <label for="profileImage">Image de profil :</label>

                
                <?php
$userImage = $user['photo']; 
if (!empty($userImage)) {
    $base64Image = base64_encode($userImage);
    $imageSrc = "data:image/jpeg;base64, " . $base64Image;
    echo '<img id="previewImage" src=" '. $imageSrc .' " alt="User Photo" class="user-photo"><br>';
} else {
    echo '<img id="previewImage" src="images/background.png" alt="User Photo" class="user-photo"><br>';
}
?>
        <input type="file" id="profileImage" name="profileImage" accept="image/*">
        <label for="bio">Biographie :</label><br>
        <textarea id="bio" name="citation"><?php echo ($user['citation'] ?? 'Ajouter une citation qui décrit votre envie de Voyager !'); ?></textarea>
        <input type="submit" value="Enregistrer" class="btn btn-primary custom-button mt-1 mb-3">
    
                </form>
            </div>
            <section id="volsSection" class="main-section">
            
            <h2 id="volsTitle">Vols favoris</h2>
            <div id="volsContainer">
            <?php
        if (!empty($registeredFlights)) {
            echo '<ul>';
            foreach ($registeredFlights as $flight) {
                echo '<li>';
                echo 'Numéro de vol: ' . $flight['num_vols'] . '<br>';
                echo 'Départ: ' . $flight['depart'] . '<br>';
                echo 'Arrivée: ' . $flight['arrive'] . '<br>';
                echo 'Date de départ: ' . $flight['datedep'] . '<br>';
                echo 'Type vol : '.$flight['type_vol'].'<br>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Aucun vol enregistré.</p>';
        }
        ?>
            </div>
        </section>
        </section>
        <img src="images/mustuse.jpg" alt="image_adroite" id="bestimage">
    </main>
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3 id="changemdp">Changer le Mot de Passe</h3>
            <form id="changepass" action="changepassword.php" method="post">
                <label class="mb-3" for="newPassword">Nouveau Mot de Passe:</label>
                <input type="password" id="newPassword" name="newPassword" class="input-text mb-3" required><br>
                <div id="errorMessages"></div>
                <input type="submit" value="Changer" class=" btn btn-primary custom-button">
            </form>
        </div>
    </div>

    <footer><?php include("lefooter.php"); ?></footer>
    <script src="scriptprofil.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    
    
</body>

</html>