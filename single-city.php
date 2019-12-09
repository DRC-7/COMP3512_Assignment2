<?php
require 'includes/dbh.inc.php';

if ($conn->connect_error) {
    exit("Error connecting to the database");
}

//----- City Details Fetch -----------------------------------------------------------------------------
if (isset($_GET['AsciiName'])) {
    $selectedCity = $_GET['AsciiName'];

    $sql = "SELECT * FROM cities WHERE AsciiName=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=Ascii-Name-Error");
        exit();
    } else {
        if ($selectedCity != null) {
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "s", $selectedCity);
        } else {
            header("Location: //index.php?error=passed-city-error");
        }

        mysqli_stmt_execute($stmt);
        $city = mysqli_stmt_get_result($stmt);
        $cityRow = mysqli_fetch_assoc($city);
    }
}
mysqli_stmt_close($stmt);

//----- Country Details Fetch --------------------------------------------------------------------------
if (isset($_GET['ISO'])) {
    $selectedCountry = $_GET['ISO'];

    $sql = "SELECT * FROM countries WHERE ISO=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=countryISOerror");
        exit();
    } else {
        if ($selectedCountry != null) {
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "s", $selectedCountry);
        } else {
            header("Location: //index.php?error=selectedCountryError");
        }

        mysqli_stmt_execute($stmt);
        $country = mysqli_stmt_get_result($stmt);
        $countryRow = mysqli_fetch_assoc($country);
    }
}
mysqli_stmt_close($stmt);

//----- City Photos Fetch ------------------------------------------------------------------------------
$photoList = "SELECT * FROM imagedetails WHERE CityCode=?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $photoList)) {
    header("Location: ../index.php?error=sqlCityPhotoError");
    exit();
} else {
    if ($selectedCity != null) {
        mysqli_stmt_prepare($stmt, $photoList);
        mysqli_stmt_bind_param($stmt, "i", $cityRow['CityCode']);
    } else {
        header("Location: //index.php?error=city-error-B");
    }

    mysqli_stmt_execute($stmt);
    $photo = mysqli_stmt_get_result($stmt);
    $photoRow = mysqli_fetch_assoc($photo);
}

//Close the connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/filter-list.css">
</head>

<header>
    <h1>COMP 3512 Assignment 2 - City Page</h1>
</header>

<body>
    <div class='main-container'>

        <div class='country-container'>
            <ul id='country-filter'>
                <h1>PLACEHOLDER FOR FILTERS</h1>
                <!-- Add the filter options for countries here -->
            </ul>
            <ul id='country-list'>
                <!-- All of the countries will be populated here as list items -->

                <!-- Get's the list of cities -->
                <form method="GET" action="http://localhost/Github/COMP-3512-A2/single-city.php?"></form>
            </ul>
        </div>

        <div class='details-container'>
            <h1><?php echo $cityRow['AsciiName']; ?></h1>
            <div id='city-details'>
                <!-- All of the data for the selected country -->
                <?php
                echo "<section class='details-list-section'>";
                echo "<label>Area:</label>";
                echo "<span id='country-area'>" . $countryRow['Area'] . "</span>";
                echo "<label>Population:</label>";
                echo "<span id='country-pop'>" . $cityRow['Population'] . "</span>";
                echo "<label>Capital City:</label>";
                echo "<span id='country-cap'>" . $countryRow['Capital'] . "</span>";
                echo "<label>Currency Name:</label>";
                echo "<span id='country-curr-name'>" . $countryRow['CurrencyName'] . "</span>";
                echo "<label>Currency Code:</label>";
                echo "<span id='country-curr-code'>" . $countryRow['CurrencyCode'] . "</span>";
                echo "<label>Domain:</label>";
                echo "<span id='country-dom'>" . $countryRow['TopLevelDomain'] . "</span>";
                echo "<label>Languages:</label>";
                echo "<span id='country-lang'>" . $countryRow['Languages'] . "</span>";
                echo "<label>Neighbours:</label?";
                echo "<span id='country-neig'>" . $countryRow['Neighbours'] . "</span>";
                echo "<label>Description:</label>";
                echo "<span id='country-desc'>" . $countryRow['CountryDescription'] . "</span>";
                echo "</section>";
                ?>
            </div>

            <div class='city-map-container'>
                <h1>City Map</h1>
                <div id='city-map'>
                    <!-- Map of the selected city -->
                </div>
            </div>

        </div>

        <div class='country-photo-container'>
            <h1>City Photos</h1>
            <div id='city-photos'>
                <!-- All of the images for the selected city -->
                <?php
                if ($photoRow != null) {
                    while ($photoRow = mysqli_fetch_assoc($photo)) {
                        //echo "<li>" . $photoRow['ImageID'] . ", " . $photoRow['Title'] . "</li>";
                        echo "<a href='single-photo.php?ImageID=" . $photoRow['ImageID'] . "'>" . "<img src=https://storage.googleapis.com/riley_comp3512_ass1_images/case-travel-master/images/square150/" . $photoRow['Path'] . "></img></a>";
                    }
                } else {
                    echo $cityRow['AsciiName'] . " Has No Photos To Display";
                }
                ?>
            </div>
        </div>


</body>
<script src="js/api-script.js"></script>

</html>

<!-- Commented Out Format Of Country Details Section 
    <section class='details-list-section'>
        <label>Area:</label>
        <span id='country-area'></span>
        <label>Population:</label>
        <span id='country-pop'></span>
        <label>Capital City:</label>
        <span id='country-cap'></span>
        <label>Currency Name:</label>
        <span id='country-curr-name'></span>
        <label>Currency Name:</label>
        <span id='country-curr-code'></span>
        <label>Domain:</label>
        <span id='country-dom'></span>
        <label>Languages:</label>
        <span id='country-lang'></span>
        <label>Neighbours:</label>
        <span id='country-neig'></span>
        <label>Description:</label>
        <span id='country-desc'></span>
    </section> -->