<?php
	$database = "if21_siim_kr";
	require_once('fnc_general.php');
	require_once("../../config.php");

	//Parandatud admin panel, kordamine eksamiks.
	function forms_for_payment(){
		$list_html = null;
		$list_html .= '<table><thead><tr><th>Nimi</th><th> Makse olek </th></tr></thead><tbody>' ."\n";

        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, firstname, lastname, payment from vp_party WHERE cancelled IS NULL");
		$stmt->bind_result($id_from_db, $firstname_from_db, $lastname_from_db, $payment_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			if(empty($payment_from_db)){
				$list_html .= "<tr> \n";
				$list_html .= "<td>" .$firstname_from_db ." " .$lastname_from_db ."</td> \n";
				$list_html .= '<td><form method="POST" action="' .htmlspecialchars($_SERVER["PHP_SELF"]) .'">' ."\n";
				$list_html .= '<input type="hidden" name="id_input" value="' .$id_from_db .'">' ."\n";
				$list_html .= '<input name="payment_submit" type="submit" value="Märgi maksnuks">' ."\n";
				$list_html .= "</form></td> \n";
				$list_html .= "</tr> \n";
			} else {
				$list_html .= "<tr> \n";
				$list_html .= "<td>".$firstname_from_db ." " .$lastname_from_db ."</td> \n";
				$list_html .= "<td>Makstud</td> \n";
				$list_html .= "</tr> \n";
			}
		}
		if(empty($list_html)){
			$list_html .= "<tr> \n";

			$list_html = "<p>Kahjuks pole peole registreerunuid!</p> \n";

			$list_html .= "</tr> \n";
		}
		
		$list_html .= "</tbody></table> \n";

		$stmt->close();
        $conn->close();
        return $list_html;
	}

	 //    //kontrollime sisestust
	 //    if($_SERVER["REQUEST_METHOD"] === "POST"){
	 //        if(isset($_POST["payment_submit"])){
	 //            if(!empty($_POST["id_input"])){
		// 			set_payment($_POST["id_input"]);
		// 		}
		// 	}
		// }
	//...

	function register_to_event($firstname_party_new, $lastname_party_new, $studentcode_party_new, $payment_party){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");

		$stmt = $conn->prepare("INSERT INTO vp_party (firstname, lastname, studentcode, payment) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("ssii", $firstname_party_new, $lastname_party_new, $studentcode_party_new, $payment_party);
		
		if($stmt->execute()){
			$notice = "Peo info lisati andmebaasi!\n";
		}else{
			$notice = "Peo info lisamisel andmebaasi tekkis tõrge: " .$stmt->error ."\n";
		}
	
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function cancel_event_registration($studentcode_party_new){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");

		$stmt = $conn->prepare("UPDATE vp_party SET cancelled = NOW() WHERE studentcode = ?");
		$stmt->bind_param("i", $studentcode_party_new);
		echo $stmt->error;
		echo $conn->error;

		if($stmt->execute()){
			$notice = "Edukalt tühistatud!";
		}else{
			$notice = "Tekkis viga: " .$stmt->error;
		}
	
		$stmt->close();
		$conn->close();
		return $notice;
	}

	//admin panel.
	function update_event_payment_status($person_id, $payment_id){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");

		if($payment_id == 1){
			$stmt = $conn->prepare("UPDATE vp_party SET payment = 1 WHERE id = ?");
			$stmt->bind_param("i", $person_id);
			echo $stmt->error;
			echo $conn->error;
	
			if($stmt->execute()){
				$notice = "Edukalt maktud pileti eest!";
			}else{
				$notice = "Tekkis viga: " .$stmt->error;
			}
		}else{
			$stmt = $conn->prepare("UPDATE vp_party SET payment = 0 WHERE id = ?");
			$stmt->bind_param("i", $person_id);
			echo $stmt->error;
			echo $conn->error;

			if($stmt->execute()){
				$notice = "Edukalt payment = 0!";
			}else{
				$notice = "Tekkis viga: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function read_all_party_people($selected){
		$html = null;
		$conn = new mysqli($GLOBALS['server_host'], $GLOBALS['server_user_name'], $GLOBALS['server_password'], $GLOBALS['database']);
		$conn->set_charset("utf8");
		
		//<option value="x" selected="">eesnimi perekonnanimi</option>
		$stmt = $conn->prepare("SELECT id, firstname, lastname, payment FROM vp_party WHERE cancelled IS NULL");
		
		$stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $payment_from_db);
		$stmt->execute();
		
		//fetch võtab järjest jne....
		while($stmt->fetch()){
			$html .= '<option value="' .$id_from_db .'"';
			if($selected == $id_from_db){
				$html .= ' selected';
			}
		$html .= '>' .$id_from_db .". " .$first_name_from_db .' ' .$last_name_from_db .'</option>' ."\n";
		}
		
		$stmt->close();
		$conn->close();
		return $html;
	}

	function read_if_this_person_has_payed($id){
		$notice = null;
		$conn = new mysqli($GLOBALS['server_host'], $GLOBALS['server_user_name'], $GLOBALS['server_password'], $GLOBALS['database']);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT payment FROM vp_party WHERE cancelled IS NULL AND id = ?");
		
		$stmt->bind_param("i", $id);
		$stmt->bind_result($notice);
		$stmt->execute();
		$stmt->fetch();
		echo $stmt->error;
		echo $conn->error;
		
		$stmt->close();
		$conn->close();
		return $notice;
	}

	//stats.
	function read_all_party_people_reg(){
		$html = null;
		$conn = new mysqli($GLOBALS['server_host'], $GLOBALS['server_user_name'], $GLOBALS['server_password'], $GLOBALS['database']);
		$conn->set_charset("utf8");
		
		//<option value="x" selected="">eesnimi perekonnanimi</option>
		$stmt = $conn->prepare("SELECT COUNT(*) FROM vp_party WHERE cancelled IS NULL");
		
		$stmt->bind_result($html);
		$stmt->execute();
		$stmt->fetch();
		
		$stmt->close();
		$conn->close();
		return $html;
	}

	function read_all_party_people_reg_and_payed(){
		$html2 = null;
		$conn = new mysqli($GLOBALS['server_host'], $GLOBALS['server_user_name'], $GLOBALS['server_password'], $GLOBALS['database']);
		$conn->set_charset("utf8");
		
		//<option value="x" selected="">eesnimi perekonnanimi</option>
		$stmt = $conn->prepare("SELECT COUNT(*) FROM vp_party WHERE cancelled IS NULL and payment = 1");
		
		$stmt->bind_result($html2);
		$stmt->execute();
		$stmt->fetch();
		
		$stmt->close();
		$conn->close();
		return $html2;
	}