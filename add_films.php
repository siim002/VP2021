<?php
	$author_name = "Siim";
	
	//tehtud valiku postitamine mällu.
	$title_form_placeholder = null;
	$year_form_placeholder = null;
	$duration_form_placeholder = null;
	$genre_form_placeholder = null;
	$studio_form_placeholder = null;
	$director_form_placeholder = null;
	
	//list mis läheb serverisse
	$film_store_notice = null;
	require_once("../../config.php");
	//echo $server_host;
	require_once("fnc_film.php");

	//kas pyytakse salvestata
	if(isset($_POST['film_submit'])){
		if(isset($_POST['title_input'])){
			$title_form_placeholder = $_POST['title_input'];
				if((!empty($_POST['title_input']))){
					$title_input = filter_var($_POST['title_input'], FILTER_SANITIZE_STRING);
					$title_form_placeholder = $_POST['title_input'];
				}
		}
		if(isset($_POST['year_input'])){
			$year_form_placeholder = htmlspecialchars($_POST['year_input']);
				if((!empty($_POST['year_input']))){
					$year_input = strval(filter_var($_POST['year_input'], FILTER_SANITIZE_NUMBER_INT));
					$year_form_placeholder = $_POST['year_input'];
				}
		}
		if(isset($_POST['duration_input'])){
			$duration_form_placeholder = htmlspecialchars($_POST['duration_input']);
				if((!empty($_POST['duration_input']))){
					$duration_input = strval(filter_var($_POST['duration_input'], FILTER_SANITIZE_NUMBER_INT));
					$duration_form_placeholder = $_POST['duration_input'];
				}
		}
		if(isset($_POST['genre_input'])){
			$genre_form_placeholder = htmlspecialchars($_POST['genre_input']);
				if((!empty($_POST['genre_input']))){
					$genre_input = filter_var($_POST['genre_input'], FILTER_SANITIZE_STRING);
					$genre_form_placeholder = $_POST['genre_input'];
				}
		}
		if(isset($_POST['studio_input'])){
			$studio_form_placeholder = htmlspecialchars($_POST['studio_input']);
				if(!empty($_POST['studio_input'])){
					$studio_input = filter_var($_POST['studio_input'], FILTER_SANITIZE_STRING);
					$studio_form_placeholder = $_POST['studio_input'];
				}
		}
		if(isset($_POST['director_input'])){
			$director_form_placeholder = htmlspecialchars($_POST['director_input']);
			if((!empty($_POST['director_input']))){
					$director_input = filter_var($_POST['director_input'], FILTER_SANITIZE_STRING);
					$director_form_placeholder = $_POST['director_input'];
				}
		}
			if((!empty($_POST['title_input'])) and (!empty($_POST['year_input'])) and (!empty($_POST['duration_input'])) and (!empty($_POST['genre_input'])) and (!empty($_POST['studio_input'])) and (!empty($_POST['director_input']))){
				$film_store_notice = store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
			}else{
					$film_store_notice = "Osa andmeid on puudu!";
			}
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, Veebiprogrammeerimine</title>
</head>
<style>
.content {
  max-width: 960px;
  margin: auto;
}
</style>
<body>
	<i><h1><?php echo $author_name; ?>, Veebiprogrammeerimine</h1></i><hr><br />
<div class="content">
	<h2>Eesti filmide lisamine andmebaasi:</h2>
	
	<!-- andmebaasi saatmine, id= on php jaoks ja name htmli jaoks -->
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
		<label for="title_input">Filmi pealkiri</label>
		<input type="text" name="title_input" id="title_input" placeholder="Filmi pealkiri" value="<?php echo htmlspecialchars($title_form_placeholder); ?>" required />
		<br />
		<label for="year_input">Valmimisaasta</label>
		<input type="number" name="year_input" id="year_input" min="1912"  value="<?php echo htmlspecialchars($year_form_placeholder); ?>" required />
		<br />
		<label for="duration_input">Kestus</label>
		<input type="number" name="duration_input" id="duration_input" min="1" placeholder="60" max="600" value="<?php echo htmlspecialchars($duration_form_placeholder); ?>" required />
		<br />
		<label for="genre_input">Žanr</label>
		<input type="text" name="genre_input" id="genre_input" placeholder="Žanr" value="<?php echo htmlspecialchars($genre_form_placeholder); ?>" required />
		<br />
		<label for="studio_input">Filmi tootja</label>
		<input type="text" name="studio_input" id="studio_input" placeholder="Filmi tootja" value="<?php echo htmlspecialchars($studio_form_placeholder); ?>" required />
		<br />
		<label for="director_input">Režissöör</label>
		<input type="text" name="director_input" id="director_input" placeholder="Režissöör" value="<?php echo htmlspecialchars($director_form_placeholder); ?>" required /> 
		<br />
		<input type="submit" name="film_submit" value="Salvesta" />
	</form>
	<br />
	<span> <?php echo $film_store_notice; ?></span>
</div>
</body>
<footer>
	<hr>
	<i><b><p>Siimu veebileht.<br /></b>
	<a href="mailto:siim02@tlu.ee">Saada mullle meil! :)</a><br />
	<i>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsisevaltvõetavat sisu!</p></i>
</footer>
</html>