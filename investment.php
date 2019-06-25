<?php session_start(); if(isset($_SESSION["username"])) {?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>INCVEST - A Learning Platform for Future Billionaires</title>
    <link rel="stylesheet" href="main.css" type="text/css">	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/jquery.Jcrop.min.js"></script>
	<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
	<script>
	
	function work(){
		document.getElementById('purch').style.display = "block";
	}
	//To fade Buy button
		function check(val)
		{
			val = parseFloat(val);
			$(document).ready(function(){
				if(val<=0.0)
				{
					$('#buyb').fadeTo(100,0.7).css({
						"backgroundColor": "gray",
						"color": "white",
						"cursor": "not-allowed",
						"boxShadow": "none",
						"textAlign": "center"
					}).prop("disabled","true");
					
					$("#buyb").prop("title",'You do not have sufficient balance.');
				}
			});
		}	
	</script>
	<script>
		//Allows only numbers in input
		$(document).ready(function(){
			$(".no").keypress(function(e){
				if(e.which>=48&&e.which<=57)
				{
				}
				else {
					return false;
				}
				
			});
		});
	</script>
	<script>
		//USED FOR PRINTING CURRENCIES WITH THEIR RANKS(Sec)
		/*
		var rankJson = <?php echo json_encode(file_get_contents('https://api.coinmarketcap.com/v2/listings/')); ?>;
		var ranking = JSON.parse(rankJson);
		var num = '1';
		document.write("");
		for(var i=0;i<ranking.data.length;i++)
		{
			document.write("$arrglo[" , "\"" , ranking.data[i].name.toLowerCase() , "\"" , "]"," =  ", ranking.data[i].id , "; ");
		}
		document.write("]");
		*/
		function deselect()
		{
			document.getElementById('bcoin').value = "";
			document.getElementById('pricing').value = "Price of Coin";
			document.getElementById('pricing').style.color = "gray";
		}
		function setnull()
		{
			$(document).ready(function(){
				$("#bnum").val("");
			});
		}
		function update(rank)//Function for dynamically updating coin prices
		{
			var link = 'https://api.coinmarketcap.com/v2/ticker/' + rank + '/?structure=array'
			var xhReq = new XMLHttpRequest();
			xhReq.open("GET", link, false);
			xhReq.send(null);
			var jsonObject = JSON.parse(xhReq.responseText);
			{
				document.getElementById('pricing').value = "$" + jsonObject.data[0].quotes.USD.price;	
				document.getElementById('pricing').style.color = "white";
				document.getElementById('sprice').value = "$" + jsonObject.data[0].quotes.USD.price;	
				document.getElementById('sprice').style.color = "white";
			}
		}
		function change()
		{
			var purch = document.getElementById("purch");
			var sell = document.getElementById("sell");
			if(purch.style.display == "block")
			{
				purch.style.display = "none";
				sell.style.display = "block";
				document.getElementById('scoin').value = "";
				document.getElementById('sprice').value = "";
				document.getElementById('snum').value = "";
				document.getElementById('sb').innerHTML = "Buy Coins";
			}
			else 
			{
				purch.style.display = "block";
				sell.style.display = "none";
				document.getElementById('pricing').value = "";
				
				document.getElementById('bcoin').value = "";
				document.getElementById('pricing').value = "";
				document.getElementById('bnum').value = "";
				document.getElementById('sb').innerHTML = "Sell Coins";
			}
		}
	</script>
</head>
<?php
		//For gathering User data
		$user = $_SESSION['username'];
		$found = 0;
		$connection = mysql_connect('localhost','root','');
		$db = mysql_select_db('investokrypt',$connection);
		$query = mysql_query("select * from Users where username='$user'", $connection);
		while($row = mysql_fetch_array($query))
		{
			if($row['username'] == $user && $_SESSION["logged"]==true)
			{
				$imgPath = $row["profileImg"];
				$found = 1;
				$amt = $row["netamount"];
				break;
			}
		}
		if($found == 0)
		{
			session_unset();
			session_destroy();
			echo "<script>alert('Some Error Occurred. Please Login Again');</script>";
			echo "<script>window.location = 'index.php';</script>";
		}
	?>
<body onload="check(<?php echo $amt?>); work();">
	<header>
		<div class="container">
				<div id="logo">
					<img src="logoname.png" alt="INCVEST.com">
				</div>
				<div id="account">
					<img src='<?php echo $imgPath?>' id="profile">
					<span><?php echo $_SESSION["username"];?></span>
					<a href="logout.php" id="logout">Logout</a>
				</div>
		</div>
	</header>
	<section id="results">
		<div class="container">
			<a href="JavaScript:void(0)" id="sb" onclick="change();">Sell Coins</a>
			<a href="results.php" id="cfb">Check Stats</a>
			<span>Current Balance: <b style="font-size:20px; color:white;">$ <?php echo round($amt,2);?></b></span>
		</div>
	</section>
	<form autocomplete="off">
		
	</form>
	<section id="purch">
		<h1>Buy Coins</h1>
			<div>
				<form action="stockSubmit.php" method="post" autocomplete="off">
					<div class="autocomplete">
						<input id="bcoin" type="text" name="bname" placeholder="Choose Currency">
					</div>
						<a id="coinc" href="JavaScript:void(0)" onclick="deselect();">&#10006;</a>
					<input id="pricing" type="text" name="bprice" placeholder="Current Price of Coin" readonly>
						<a id="numc" href="JavaScript:void(0)" onclick="setnull()" >&#10006;</a>
					<input type="text" name="bqty" id="bnum" class="no" placeholder="Number of Coins">
					<input type="submit" value="Buy" id="buyb" onmouseover="check(<?php echo $amt;?>);" class="sb">
				</form>
			</div>
	</section>
	
	<section id="sell">
		<h1>Sell Coins</h1>
		<div>
			<form action="sellStocks.php" method="post" autocomplete="off">
			<div class="autocomplete">
				<input id="scoin" type="text" name="rstocks" placeholder="Choose Currency">
			</div>
				<a id="coinc" href="JavaScript:void(0)" onclick="deselect();">&#10006;</a>
			<input type="text" id="sprice" name="sellp" placeholder="Current Price of Coin" >
				<a id="coinc" href="JavaScript:void(0)" onclick="deselect();">&#10006;</a>			
			<input id="snum" type="text" name="rqty" class="no" placeholder="Number of Coins"/>
			<input type="submit" value="Sell" id="sellb"/>
			</form>
		</div>
	</section>
	
	<script>
	function autocomplete(inp, arr) {

  var currentFocus;
  inp.addEventListener("input", function(e) {
	  
      var a, b, i, val = this.value;
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      a = document.createElement("DIV");
      a.setAttribute("id", "autocontainer");
      a.setAttribute("class", "autocontainerclass");
      this.parentNode.appendChild(a);
      for (i = 0; i < arr.length; i++) {
        if (arr[i][0].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          b = document.createElement("DIV");
          b.innerHTML = "<strong>" + arr[i][0].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i][0].substr(val.length);
          b.innerHTML += "<input type='hidden' value='" + arr[i][0] + "'>";
          b.innerHTML += "<input type='hidden' value='" + arr[i][1] + "'>";
          b.addEventListener("click", function(e) {
              inp.value = this.getElementsByTagName("input")[0].value;
			  update(this.getElementsByTagName("input")[1].value);
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById("autocontainer");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) //Down Key
	  {
        currentFocus++;
		addActive(x);
      } else if (e.keyCode == 38) //Up key
	  {
        currentFocus--;
        addActive(x);
      } else if (e.keyCode == 13) //Prevention from submission on pressing Enter
	  {
        e.preventDefault();
        if (currentFocus > -1) 
		{
          if (x) x[currentFocus].click();
        }
      }

  });
  function addActive(x) {
    if (!x) return false;
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    var x = document.getElementsByClassName("autocontainerclass");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
      });
}
var arr= [["Bitcoin","1"], ["Litecoin","2"], ["Namecoin","3"], ["Terracoin","4"], ["Peercoin","5"], ["Novacoin","6"], ["Feathercoin","8"], ["Mincoin","9"], ["Freicoin","10"], ["Ixcoin","13"], ["BitBar","14"], ["WorldCoin","16"], ["Digitalcoin","18"], ["GoldCoin","25"], ["Argentum","31"], ["Fastcoin","32"], ["Bitgem","34"], ["Phoenixcoin","35"], ["Megacoin","37"], ["Infinitecoin","41"], ["Primecoin","42"], ["Anoncoin","43"], ["CasinoCoin","45"], ["Bullion","49"], ["Emerald Crypto","50"], ["GlobalCoin","51"], ["Ripple","52"], ["Quark","53"], ["Zetacoin","56"], ["SecureCoin","57"], ["Sexcoin","58"], ["TagCoin","61"], ["I0Coin","63"], ["FlorinCoin","64"], ["Nxt","66"], ["Unobtanium","67"], ["Joulecoin","68"], ["Datacoin","69"], ["BetaCoin","70"], ["GrandCoin","71"], ["Deutsche eMark","72"], ["Dogecoin","74"], ["NetCoin","75"], ["Philosopher Stones","76"], ["Diamond","77"], ["HoboNickels","78"], ["Tigercoin","79"], ["Orbitcoin","80"], ["Omni","83"], ["Catcoin","84"], ["FedoraCoin","87"], ["RonPaulCoin","88"], ["Mooncoin","89"], ["Dimecoin","90"], ["42-coin","93"], ["Vertcoin","99"], ["KlondikeCoin","101"], ["RedCoin","103"], ["DigiByte","109"], ["SmartCoin","113"], ["TeslaCoin","114"], ["NobleCoin","117"], ["ReddCoin","118"], ["Nyancoin","120"], ["UltraCoin","121"], ["PotCoin","122"], ["Blakecoin","125"], ["MaxCoin","128"], ["QubitCoin","129"], ["HunterCoin","130"], ["Dash","131"], ["Counterparty","132"], ["CacheCoin","134"], ["TopCoin","135"], ["iCoin","138"], ["Mintcoin","141"], ["Aricoin","142"], ["DopeCoin","145"], ["Auroracoin","148"], ["Animecoin","150"], ["Pesetacoin","151"], ["Marscoin","154"], ["Cashcoin","159"], ["Riecoin","160"], ["Pandacoin","161"], ["MAZA","164"], ["Uniform Fiscal Object","168"], ["BlackCoin","170"], ["LiteBar","174"], ["Photon","175"], ["Zeitcoin","181"], ["Myriad","182"], ["Skeincoin","199"], ["Einsteinium","201"], ["Bitcoin Scrypt","205"], ["Coin(O)","206"], ["ECC","212"], ["MonaCoin","213"], ["Rubycoin","215"], ["Bela","217"], ["FlutterCoin","218"], ["OctoCoin","221"], ["FairCoin","224"], ["SolarCoin","233"], ["e-Gulden","234"], ["Gulden","254"], ["Polcoin","257"], ["Groestlcoin","258"], ["PetroDollar","260"], ["PLNcoin","263"], ["WhiteCoin","268"], ["AsiaCoin","269"], ["PopularCoin","275"], ["Bitstar","276"], ["Quebecoin","278"], ["CannaCoin","279"], ["Slothcoin","287"], ["BlueCoin","290"], ["MaidSafeCoin","291"], ["Bitcoin Plus","293"], ["BTCtalkcoin","295"], ["NewYorkCoin","298"], ["Canada eCoin","304"], ["Guncoin","312"], ["PinkCoin","313"], ["Dreamcoin","316"], ["CoffeeCoin","317"], ["Energycoin","322"], ["VeriCoin","323"], ["TEKcoin","325"], ["Monero","328"], ["Litecoin Plus","331"], ["Curecoin","333"], ["UnbreakableCoin","334"], ["CryptCoin","337"], ["QuazarCoin","338"], ["SuperCoin","341"], ["Qora","344"], ["BoostCoin","350"], ["Hyper","353"], ["BitQuark","356"], ["Motocoin","360"], ["CloakCoin","362"], ["BitSend","366"], ["Coin2.1","367"], ["Fantomcoin","370"], ["Bytecoin","372"], ["ArtByte","374"], ["NavCoin","377"], ["Granite","382"], ["Donationcoin","385"], ["Piggycoin","386"], ["Startcoin","389"], ["Kore","400"], ["DigitalNote","405"], ["Boolberry","406"], ["SHACoin","411"], ["BlazeCoin","415"], ["HempCoin","416"], ["BritCoin","426"], ["Stealth","448"], ["TrustPlus","450"], ["Clams","460"], ["Quatloo","461"], ["BitShares","463"], ["BitcoinDark","467"], ["Truckcoin","468"], ["Viacoin","470"], ["Firecoin","476"], ["Triangles","477"], ["ShadowCash","493"], ["I/O Coin","495"], ["Cryptonite","501"], ["Carboncoin","502"], ["CannabisCoin","506"], ["Stellar","512"], ["Titcoin","513"], ["Virtacoin","520"], ["HyperStake","525"], ["Joincoin","536"], ["Syscoin","541"], ["Bitmark","543"], ["Halcyon","545"], ["Storjcoin X","549"], ["NeosCoin","551"], ["Emercoin","558"], ["RabbitCoin","572"], ["Burst","573"], ["GameCredits","576"], ["WeAreSatoshi","584"], ["Ubiq","588"], ["BunnyCoin","594"], ["Opal","597"], ["Acoin","601"], ["FoldingCoin","606"], ["bitUSD","623"], ["bitCNY","624"], ["bitBTC","625"], ["NuBits","626"], ["Sterlingcoin","627"], ["Magi","629"], ["ExclusiveCoin","633"], ["Trollcoin","638"], ["SuperNET","643"], ["GlobalBoost-Y","644"], ["DigitalPrice","654"], ["Prime-XI","656"], ["Bitswift","659"], ["Dashcoin","660"], ["AurumCoin","666"], ["Sativacoin","680"], ["Verge","693"], ["NuShares","699"], ["SpreadCoin","702"], ["Rimbit","703"], ["MonetaryUnit","706"], ["Blocknet","707"], ["Gapcoin","708"], ["TittieCoin","719"], ["Crown","720"], ["BitBay","723"], ["GCN Coin","730"], ["Quotient","733"], ["Bytecent","734"], ["Bitcoin Fast","747"], ["OKCash","760"], ["PayCoin","764"], ["vTorrent","766"], ["bitGold","778"], ["Unitus","781"], ["GoldPieces","785"], ["Circuits of Value","788"], ["Nexus","789"], ["SoonCoin","795"], ["MetalCoin","796"], ["IncaKoin","797"], ["SmileyCoin","799"], ["Machinecoin","812"], ["bitSilver","813"], ["Dotcoin","814"], ["Kobocoin","815"], ["PayCon","818"], ["Bean Cash","819"], ["GeoCoin","823"], ["Tether","825"], ["Wild Beast Block","831"], ["GridCoin","833"], ["Vcash","836"], ["X-Coin","837"], ["Sharkcoin","841"], ["Bitz","842"], ["LiteDoge","853"], ["UNCoin","855"], ["SongCoin","857"], ["Woodcoin","859"], ["Crave","869"], ["Pura","870"], ["NEM","873"], ["8Bit","890"], ["LeaCoin","892"], ["Neutron","894"], ["Xaurum","895"], ["Californium","898"], ["Advanced Internet Blocks","911"], ["EggCoin","912"], ["Sphere","914"], ["MedicCoin","916"], ["Bubble","918"], ["MUSE","920"], ["Universal Currency","921"], ["ShellCoin","924"], ["Crypto","933"], ["ParkByte","934"], ["ARbit","938"], ["Gambit","939"], ["Bata","945"], ["AudioCoin","948"], ["Synergy","951"], ["bitEUR","954"], ["UniCoin","959"], ["FujiCoin","960"], ["EuropeCoin","964"], ["The Cypherfunks","965"], ["Hexx","977"], ["Ratecoin","978"], ["Metal Music Coin","983"], ["CrevaCoin","986"], ["IrishCoin","988"], ["Bitzeny","990"], ["Cycling Coin","992"], ["BowsCoin","993"], ["AnarchistsPrime","994"], ["CompuCoin","998"], ["ChainCoin","999"], ["Sprouts","1002"], ["Helleniccoin","1004"], ["Capricoin","1008"], ["Flaxscript","1010"], ["Manna","1019"], ["Axiom","1020"], ["LEOcoin","1022"], ["Aeon","1026"], ["Ethereum","1027"], ["SJWCoin","1028"], ["TransferCoin","1032"], ["GuccioneCoin","1033"], ["AmsterdamCoin","1035"], ["Eurocoin","1038"], ["Siacoin","1042"], ["Global Currency Reserve","1044"], ["SatoshiMadness","1048"], ["Shift","1050"], ["VectorAI","1052"], ["Bolivarcoin","1053"], ["SpaceCoin","1058"], ["Bitcrystals","1063"], ["Pakcoin","1066"], ["Influxcoin","1069"], ["Expanse","1070"], ["SIBCoin","1082"], ["IslaCoin","1084"], ["Swing","1085"], ["Factom","1087"], ["ParallelCoin","1089"], ["Save and Gain","1090"], ["Prototanium","1093"], ["DigiCube","1100"], ["Augur","1104"], ["StrongHands","1106"], ["PACcoin","1107"], ["Elite","1109"], ["Money","1110"], ["SOILcoin","1111"], ["SecretCoin","1113"], ["DraftCoin","1120"], ["OBITS","1123"], ["Synereo","1125"], ["GBCGoldCoin","1128"], ["X2","1131"], ["ClubCoin","1135"], ["Adzcoin","1136"], ["MindCoin","1139"], ["Moin","1141"], ["AvatarCoin","1146"], ["RussiaCoin","1147"], ["EverGreenCoin","1148"], ["Creditbit","1153"], ["Radium","1154"], ["Litecred","1155"], ["Yocoin","1156"], ["SaluS","1159"], ["Francs","1164"], ["Evil Coin","1165"], ["Decred","1168"], ["PIVX","1169"], ["Safe Exchange Coin","1172"], ["Rubies","1175"], ["Asiadigicoin","1176"], ["Destiny","1180"], ["KiloCoin","1182"], ["TrumpCoin","1185"], ["Memetic / PepeCoin","1191"], ["C-Bit","1193"], ["Independent Money System","1194"], ["HOdlcoin","1195"], ["BigUp","1198"], ["NevaCoin","1200"], ["BumbaCoin","1206"], ["RevolutionVR","1208"], ["PosEx","1209"], ["Cabbage","1210"], ["MojoCoin","1212"], ["GoldMaxCoin","1213"], ["Lisk","1214"], ["EDRCoin","1216"], ["PostCoin","1218"], ["Operand","1222"], ["BERNcash","1223"], ["Qwark","1226"], ["DigixDAO","1229"], ["Steem","1230"], ["Fantasy Cash","1234"], ["Espers","1238"], ["FuzzBalls","1241"], ["HiCoin","1244"], ["AquariusCoin","1247"], ["Bitcoin 21","1248"], ["Elcoin","1249"], ["Zurcoin","1250"], ["SixEleven","1251"], ["2GIVE","1252"], ["PlatinumBAR","1254"], ["Alphabit","1256"], ["LanaCoin","1257"], ["PonziCoin","1259"], ["TeslaCoilCoin","1264"], ["MarteXcoin","1266"], ["Nullex","1268"], ["RichCoin","1269"], ["PrismChain","1271"], ["Waves","1274"], ["Newbium","1275"], ["ICO OpenLedger","1276"], ["Powercoin","1279"], ["ION","1281"], ["High Voltage","1282"], ["Mineum","1283"], ["RevolverCoin","1284"], ["GoldBlocks","1285"], ["Breakout","1286"], ["Debitcoin","1288"], ["Comet","1291"], ["Rise","1294"], ["ChessCoin","1297"], ["LBRY Credits","1298"], ["PutinCoin","1299"], ["Breakout Stake","1303"], ["Syndicate","1304"], ["Cryptojacks","1306"], ["ReeCoin","1307"], ["HEAT","1308"], ["LetItRide","1309"], ["DAPPSTER","1310"], ["Steem Dollars","1312"], ["LinkedCoin","1313"], ["Ardor","1320"], ["Ethereum Classic","1321"], ["808Coin","1322"], ["First Bitcoin","1323"], ["PokeCoin","1330"], ["Elementrem","1334"], ["UGAIN","1336"], ["Karbo","1340"], ["VapersCoin","1341"], ["Stratis","1343"], ["President Trump","1348"], ["Aces","1351"], ["President Johnson","1352"], ["TajCoin","1353"], ["TodayCoin","1356"], ["PX","1357"], ["E-Dinar Coin","1358"], ["BitTokens","1359"], ["Artex Coin","1361"], ["Experience Points","1367"], ["Veltor","1368"], ["B3Coin","1371"], ["BlockPay","1374"], ["Golfcoin","1375"], ["NEO","1376"], ["Jewels","1379"], ["LoMoCoin","1380"], ["Bitcloud","1381"], ["NoLimitCoin","1382"], ["SportsCoin","1385"], ["VeriumReserve","1387"], ["Zayedcoin","1389"], ["Jin Coin","1390"], ["Tao","1391"], ["Pluton","1392"], ["Tellurion","1393"], ["Dollarcoin","1395"], ["MustangCoin","1396"], ["Beatcoin","1397"], ["PROUD Money","1398"], ["Sequence","1399"], ["Omicron","1400"], ["FirstBlood","1403"], ["Pepe Cash","1405"], ["Iconomi","1408"], ["SingularDTV","1409"], ["ZCoin","1414"], ["Rcoin","1418"], ["Atomic Coin","1420"], ["JobsCoin","1421"], ["Triggers","1423"], ["Sakuracoin","1425"], ["Levocoin","1429"], ["Advanced Technology Coin","1434"], ["Cubits","1435"], ["DynamicCoin","1436"], ["Zcash","1437"], ["ImpulseCoin","1438"], ["AllSafe","1439"], ["BipCoin","1442"], ["ZClassic","1447"], ["Zoin","1448"], ["WA Space","1449"], ["Lykke","1454"], ["Golem","1455"], ["ZetaMicron","1456"], ["Bitcurrency","1457"], ["Regacoin","1459"], ["LuckChain","1463"], ["Internet of People","1464"], ["Veros","1465"], ["Hush","1466"], ["Kurrent","1468"], ["Creatio","1469"], ["Rawcoin","1472"], ["Pascal Coin","1473"], ["Eternity","1474"], ["Incent","1475"], ["DECENT","1478"], ["NodeCoin","1479"], ["Golos","1480"], ["Nexium","1481"], ["Shorty","1482"], ["vSlice","1483"], ["Dollar Online","1485"], ["Vault Coin","1486"], ["Pabyosi Coin (Special)","1487"], ["T-coin","1489"], ["Byteball Bytes","1492"], ["PoSW Coin","1495"], ["Luna Coin","1496"], ["Fargocoin","1497"], ["Wings","1500"], ["Dix Asset","1501"], ["GameUnits","1502"], ["Darcrus","1503"], ["InflationCoin","1504"], ["Spectrecoin","1505"], ["Safe Trade Coin","1506"], ["LandCoin","1507"], ["BenjiRolls","1509"], ["CryptoCarbon","1510"], ["PureVidz","1511"], ["BitConnect","1513"], ["ICOBID","1514"], ["iBank","1515"], ["Maker","1518"], ["Digital Rupees","1520"], ["Komodo","1521"], ["FirstCoin","1522"], ["Magnum","1523"], ["Tattoocoin (Standard Edition)","1524"], ["Solarflarecoin","1525"], ["Zilbercoin","1526"], ["Waves Community Token","1527"], ["Iconic","1528"], ["KushCoin","1529"], ["LePen","1530"], ["Global Cryptocurrency","1531"], ["Avoncoin","1533"], ["BOAT","1534"], ["Eryllium","1535"], ["Elysium","1539"], ["Golos Gold","1542"], ["CryptoWorldX Token","1544"], ["Centurion","1546"], ["Marijuanacoin","1548"], ["Master Swiscoin","1550"], ["KashhCoin","1551"], ["Melon","1552"], ["Allion","1554"], ["PRCoin","1555"], ["Chronobank","1556"], ["Argus","1558"], ["Renos","1559"], ["UR","1561"], ["Swarm City","1562"], ["PIECoin","1563"], ["MarxCoin","1565"], ["Visio","1566"], ["Nano","1567"], ["LevoPlus","1568"], ["GeertCoin","1570"], ["Pascal Lite","1575"], ["MiloCoin","1576"], ["Musicoin","1577"], ["Zero","1578"], ["BioBar","1579"], ["Honey","1581"], ["Netko","1582"], ["Ark","1586"], ["Dynamic","1587"], ["Tokes","1588"], ["Mercury","1590"], ["TaaS","1592"], ["Soarcoin","1595"], ["Edgeless","1596"], ["Bankcoin","1597"], ["ZSEcoin","1598"], ["HealthyWormCoin","1602"], ["Databits","1603"], ["BestChain","1604"], ["Universe","1605"], ["Solaris","1606"], ["Impact","1607"], ["Tristar Coin","1608"], ["Asch","1609"], ["Scorecoin","1610"], ["DubaiCoin","1611"], ["Darsek","1614"], ["Matchpool","1616"], ["Ultimate Secure Cash","1617"], ["E-coin","1618"], ["Skycoin","1619"], ["BlazerCoin","1623"], ["Atmos","1624"], ["Ethereum Movie Venture","1625"], ["InPay","1626"], ["Happycoin","1628"], ["Zennies","1629"], ["Coinonat","1630"], ["Internet of Things","1631"], ["Concoin","1632"], ["Ebittree Coin","1634"], ["Fazzcoin","1635"], ["XTRABYTES","1636"], ["iExec RLC","1637"], ["WeTrust","1638"], ["DeusCoin","1640"], ["Altcoin","1642"], ["WavesGo","1643"], ["MACRON","1644"], ["Tattoocoin (Limited Edition)","1647"], ["ProCurrency","1650"], ["SpeedCash","1651"], ["Bitcore","1654"], ["Bitvolt","1657"], ["Lunyr","1658"], ["Gnosis","1659"], ["TokenCard","1660"], ["Condensate","1662"], ["Gold Pressed Latinum","1665"], ["Humaniq","1669"], ["FUNCoin","1670"], ["iTicoin","1671"], ["Minereum","1673"], ["Cannation","1674"], ["Creativecoin","1676"], ["Etheroll","1677"], ["InsaneCoin","1678"], ["Halloween Coin","1679"], ["Aragon","1680"], ["PRIZM","1681"], ["RouletteToken","1683"], ["Qtum","1684"], ["EcoCoin","1685"], ["EquiTrader","1686"], ["Digital Money Bits","1687"], ["APX","1688"], ["MCAP","1690"], ["Project-X","1691"], ["Theresa May Coin","1693"], ["Sumokoin","1694"], ["ZenGold","1695"], ["Basic Attention Token","1697"], ["ZenCash","1698"], ["Ethbits","1699"], ["Aeternity","1700"], ["Version","1702"], ["Metaverse ETP","1703"], ["eBoost","1704"], ["Aidos Kuneen","1706"], ["STEX","1707"], ["Patientory","1708"], ["Quantum","1709"], ["Veritaseum","1710"], ["Electra","1711"], ["Quantum Resistant Ledger","1712"], ["EncryptoTel [WAVES]","1714"], ["MobileGo","1715"], ["Ammo Reloaded","1716"], ["Neuro","1717"], ["Peerplays","1719"], ["IOTA","1720"], ["Mysterium","1721"], ["Legends Room","1722"], ["SONM","1723"], ["Linx","1724"], ["ZrCoin","1726"], ["Bancor","1727"], ["Cheapcoin","1728"], ["Cofound.it","1729"], ["GlobalToken","1731"], ["Numeraire","1732"], ["Octanox","1733"], ["Unify","1736"], ["Elastic","1737"], ["Coupecoin","1738"], ["Miners' Reward Token","1739"], ["Bitok","1741"], ["Huncoin","1742"], ["KingN Coin","1743"], ["Dinastycoin","1745"], ["Leviar","1746"], ["Onix","1747"], ["Bitcoin Planet","1748"], ["GXChain","1750"], ["Goodomy","1752"], ["Antimatter","1753"], ["Bitradio","1754"], ["Flash","1755"], ["Pirate Blocks","1756"], ["FunFair","1757"], ["TenX","1758"], ["Status","1759"], ["ChanCoin","1760"], ["Ergo","1762"], ["BriaCoin","1763"], ["EOS","1765"], ["TurboCoin","1767"], ["AdEx","1768"], ["Denarius","1769"], ["DAO.Casino","1771"], ["Storj","1772"], ["BnrtxCoin","1773"], ["SocialCoin","1774"], ["adToken","1775"], ["Monaco","1776"], ["CryptoPing","1777"], ["Useless Ethereum Token","1778"], ["Wagerr","1779"], ["Slevin","1781"], ["Ecobit","1782"], ["UniversalRoyalCoin","1783"], ["Polybius","1784"], ["Gas","1785"], ["SunContract","1786"], ["Jetcoin","1787"], ["Metal","1788"], ["Populous","1789"], ["WomenCoin","1790"], ["Virta Unique Coin","1792"], ["Bitdeal","1793"], ["Sphre AIR ","1795"], ["DaxxCoin","1797"], ["FuturXe","1798"], ["Rupee","1799"], ["FinCoin","1800"], ["Global Tour Coin","1801"], ["PeepCoin","1803"], ["Sovereign Hero","1805"], ["Santiment Network Token","1807"], ["OmiseGO","1808"], ["TerraNova","1809"], ["CVCoin","1810"], ["Nimiq Exchange Token","1811"], ["Rialto","1812"], ["Linda","1814"], ["Embers","1815"], ["Civic","1816"], ["Ethos","1817"], ["Bit20","1818"], ["Starta","1819"], ["RSGPcoin","1822"], ["BitCoal","1824"], ["LiteBitcoin","1825"], ["Particl","1826"], ["Primulon","1827"], ["SmartCash","1828"], ["SkinCoin","1830"], ["Bitcoin Cash","1831"], ["HarmonyCoin","1832"], ["ToaCoin","1833"], ["Pillar","1834"], ["Royalties","1835"], ["Signatum","1836"], ["Coimatic 2.0","1837"], ["OracleChain","1838"], ["Binance Coin","1839"], ["300 Token","1840"], ["Primalbase Token","1841"], ["CampusCoin","1842"], ["EmberCoin","1843"], ["iXledger","1845"], ["GeyserCoin","1846"], ["Mothership","1847"], ["Aseancoin","1848"], ["Birds","1849"], ["Cream","1850"], ["ERA","1851"], ["KekCoin","1852"], ["OAX","1853"], ["district0x","1856"], ["FundYourselfNow","1857"], ["Stox","1861"], ["Minex","1863"], ["Blox","1864"], ["Wink","1865"], ["Bytom","1866"], ["Mao Zedong","1869"], ["First Bitcoin Capital","1871"], ["NEVERDIE","1872"], ["Blocktix","1873"], ["Dentacoin","1876"], ["Rupaya","1877"], ["Shadow Token","1878"], ["DeepOnion","1881"], ["BlockCAT","1882"], ["AdShares","1883"], ["DigitalDevelopersFund","1884"], ["BitAsean","1885"], ["Dent","1886"], ["Monster Byte","1887"], ["InvestFeed","1888"], ["CoinonatX","1889"], ["Etheriya","1890"], ["The ChampCoin","1894"], ["0x","1896"], ["Bolenum","1897"], ["Smoke","1898"], ["YOYOW","1899"], ["Growers International","1900"], ["MyBit Token","1902"], ["Hshare","1903"], ["VeChain","1904"], ["TrueFlip","1905"], ["XTD Coin","1907"], ["Nebulas","1908"], ["ATMCoin","1910"], ["Dalecoin","1912"], ["Protean","1913"], ["AdCoin","1915"], ["BiblePay","1916"], ["bitqy","1917"], ["Achain","1918"], ["NamoCoin","1920"], ["SIGMAcoin","1921"], ["Monoeci","1922"], ["Tierion","1923"], ["Waltonchain","1925"], ["BROTHER","1926"], ["Ulatech","1927"], ["Primas","1930"], ["Opus","1931"], ["Suretly","1933"], ["Loopring","1934"], ["LiteCoin Ultra","1935"], ["Po.et","1937"], ["Fujinto","1938"], ["StarCredits","1942"], ["Kronecoin","1943"], ["Cyder","1945"], ["Masternodecoin","1946"], ["Monetha","1947"], ["Aventus","1948"], ["Agrello","1949"], ["Hive Project","1950"], ["Vsync","1951"], ["Magnetcoin","1952"], ["Moeda Loyalty Points","1954"], ["Neblio","1955"], ["VIVO","1956"], ["TRON","1958"], ["Oceanlab","1959"], ["imbrex","1961"], ["BuzzCoin","1962"], ["Credo","1963"], ["DROXNE","1964"], ["Bowhead","1965"], ["Decentraland","1966"], ["Indorse Token","1967"], ["XPA","1968"], ["Sociall","1969"], ["ATBCoin","1970"], ["iQuant","1971"], ["Ethereum Dark","1973"], ["Propy","1974"], ["ChainLink","1975"], ["Blackmoon","1976"], ["Wi Coin","1979"], ["Elixir","1980"], ["Billionaire Token","1981"], ["Kyber Network","1982"], ["VIBE","1983"], ["Substratum","1984"], ["Chronologic","1985"], ["CHIPS","1986"], ["CryptoInsight","1987"], ["Lampix","1988"], ["COSS","1989"], ["BitDice","1990"], ["Rivetz","1991"], ["Kin","1993"], ["Interzone","1994"], ["Target Coin","1995"], ["SALT","1996"], ["India Coin","1997"], ["Ormeus Coin","1998"], ["Kolion","1999"], ["Musiconomi","2000"], ["ColossusXT","2001"], ["TrezarCoin","2002"], ["HODL Bucks","2004"], ["Obsidian","2005"], ["Cobinhood","2006"], ["Regalcoin","2007"], ["MSD","2008"], ["Bismuth","2009"], ["Cardano","2010"], ["Tezos (Pre-Launch)","2011"], ["Voise","2012"], ["Infinity Economics","2013"], ["ATMChain","2015"], ["KickCoin","2017"], ["EncryptoTel [ETH]","2018"], ["Viberate","2019"], ["RChain","2021"], ["Internxt","2022"], ["WhaleCoin","2024"], ["FLiK","2025"], ["EthBet","2026"], ["Cryptonex","2027"], ["Wild Crypto","2029"], ["REAL","2030"], ["Hubii Network","2031"], ["Crystal Clear ","2032"], ["BridgeCoin","2033"], ["Everex","2034"], ["PayPie","2036"], ["AirToken","2037"], ["PoSToken","2038"], ["Senderon","2039"], ["ALIS","2040"], ["BitcoinZ","2041"], ["HelloGold","2042"], ["Cindicator","2043"], ["Enigma","2044"], ["Coimatic 3.0","2045"], ["Bastonet","2046"], ["Zeusshield","2047"], ["Ethereum Cash","2048"], ["CORION","2049"], ["Swisscoin","2050"], ["Authorship","2051"], ["Royal Kingdom Coin","2053"], ["Akuya Coin","2054"], ["ExchangeN","2055"], ["PiplCoin","2056"], ["Eidoo","2057"], ["AirSwap","2058"], ["BitSoar","2059"], ["Change","2060"], ["BlockMason Credit Protocol","2061"], ["Aion","2062"], ["Tracto","2063"], ["Maecenas","2064"], ["XGOX","2065"], ["Everus","2066"], ["Dutch Coin","2067"], ["Open Trading Network","2069"], ["DomRaider","2070"], ["Request Network","2071"], ["SegWit2x","2072"], ["Ethereum Gold","2074"], ["Blue Protocol","2076"], ["Runners","2077"], ["LIFE","2078"], ["Hedge","2079"], ["Modum","2080"], ["Ambrosus","2081"], ["ICOS","2082"], ["Bitcoin Gold","2083"], ["KuCoin Shares","2087"], ["EXRNchain","2088"], ["ClearPoll","2089"], ["LATOKEN","2090"], ["Exchange Union","2091"], ["Nuls","2092"], ["Bitcoin Red","2093"], ["Paragon","2094"], ["BOScoin","2095"], ["Ripio Credit Network","2096"], ["Mercury Protocol","2098"], ["ICON","2099"], ["JavaScript Token","2100"], ["Ethereum Lite","2101"], ["Intelligent Trading Foundation","2103"], ["iEthereum","2104"], ["Pirl","2105"], ["Xenon","2106"], ["LUXCoin","2107"], ["eREAL","2108"], ["Network Token","2109"], ["Dovu","2110"], ["Red Pulse","2112"], ["BT2 [CST]","2114"], ["PlayerCoin","2115"], ["Roofs","2117"], ["FAPcoin","2118"], ["BTCMoon","2119"], ["Etherparty","2120"], ["Ellaism","2122"], ["Vulcano","2123"], ["Qvolta","2124"], ["Russian Miner Coin","2125"], ["FlypMe","2126"], ["eBitcoin","2127"], ["Bitbase","2129"], ["Enjin Coin","2130"], ["Power Ledger","2132"], ["Grid+","2134"], ["Revain","2135"], ["ATLANT","2136"], ["Electroneum","2137"], ["High Gain","2138"], ["MinexCoin","2139"], ["SONO","2140"], ["FORCE","2142"], ["Streamr DATAcoin","2143"], ["SHIELD","2144"], ["Pure","2146"], ["ELTCOIN","2147"], ["Desire","2148"], ["Unikoin Gold","2149"], ["Credence Coin","2150"], ["Autonio","2151"], ["CarTaxi Token","2152"], ["Aeron","2153"], ["StarCash Network","2157"], ["Phore","2158"], ["Farad","2159"], ["Innova","2160"], ["Raiden Network Token","2161"], ["Delphy","2162"], ["Zephyr","2163"], ["ERC20","2165"], ["Ties.DB","2166"], ["Blockpool","2167"], ["Grimcoin","2168"], ["Oxycoin","2170"], ["Abjcoin","2171"], ["Emphy","2172"], ["Interstellar Holdings","2173"], ["NEO GOLD","2174"], ["DecentBet","2175"], ["Decision Token","2176"], ["Sharechain","2177"], ["Upfiring","2178"], ["Hat.Exchange","2179"], ["bitJob","2180"], ["Genesis Vision","2181"], ["EagleCoin","2182"], ["EA Coin","2183"], ["Privatix","2184"], ["IntenseCoin","2185"], ["PlusCoin","2186"], ["EBCH","2187"], ["SISA","2189"], ["Astro","2190"], ["Paypex","2191"], ["GOLD Reward Token","2192"], ["Aerium","2193"], ["BitSerial","2194"], ["Sugar Exchange","2196"], ["Viuly","2198"], ["ALQO","2199"], ["GoByte","2200"], ["WINCOIN","2201"], ["Oyster","2202"], ["B2BX","2204"], ["Phantomx","2205"], ["DigiPulse","2207"], ["EncrypGen","2208"], ["Ink","2209"], ["Bodhi","2211"], ["Quantstamp","2212"], ["QASH","2213"], ["ZoZoCoin","2214"], ["Energo","2215"], ["Publica","2217"], ["Magnet","2218"], ["SpankChain","2219"], ["VoteCoin","2221"], ["Bitcoin Diamond","2222"], ["BLOCKv","2223"], ["POLY AI","2224"], ["Accelerator Network","2225"], ["PlexCoin","2228"], ["Divi","2229"], ["Monkey Project","2230"], ["Flixxo","2231"], ["GlassCoin","2232"], ["Time New Bank","2235"], ["MyWish","2236"], ["EventChain","2237"], ["ETHLend","2239"], ["onG.social","2240"], ["Ccore","2241"], ["Qbao","2242"], ["Dragonchain","2243"], ["Payfair","2244"], ["Presearch","2245"], ["CyberMiles","2246"], ["BlockCDN","2247"], ["Cappasity","2248"], ["Eroscoin","2249"], ["MagicCoin","2250"], ["IoT Chain","2251"], ["Jiyo","2253"], ["Social Send","2255"], ["Bonpay","2256"], ["Nekonium","2257"], ["Snovio","2258"], ["StrikeBitClub","2259"], ["Bulwark","2260"], ["SagaCoin","2261"], ["COMSA [ETH]","2262"], ["Kubera Coin","2263"], ["Tokugawa","2264"], ["Pioneer Coin","2265"], ["COMSA [XEM]","2266"], ["WaBi","2267"], ["CrowdCoin","2268"], ["WandX","2269"], ["SportyCo","2270"], ["Verify","2271"], ["Soma","2272"], ["Uquid Coin","2273"], ["MediShares","2274"], ["ProChain","2275"], ["Ignis","2276"], ["SmartMesh","2277"], ["HollyWoodCoin","2278"], ["Playkey","2279"], ["Filecoin [Futures]","2280"], ["BitcoinX","2281"], ["Super Bitcoin","2282"], ["Datum","2283"], ["Trident Group","2284"], ["Bitair","2285"], ["MicroMoney","2286"], ["LockTrip","2287"], ["Worldcore","2288"], ["Gifto","2289"], ["YENTEN","2290"], ["Genaro Network","2291"], ["United Bitcoin","2293"], ["Starbase","2295"], ["OST","2296"], ["Storm","2297"], ["Dynamic Trading Rights","2298"], ["aelf","2299"], ["WAX","2300"], ["MediBloc","2303"], ["DEW","2304"], ["NAGA","2305"], ["Bread","2306"], ["Bibox Token","2307"], ["Dai","2308"], ["SophiaTX","2309"], ["Bounty0x","2310"], ["Ace","2311"], ["DIMCOIN","2312"], ["SIRIN LABS Token","2313"], ["Cryptopay","2314"], ["HTMLCOIN","2315"], ["DeepBrain Chain","2316"], ["HomeBlockCoin","2317"], ["Neumark","2318"], ["UTRUST","2320"], ["QLINK","2321"], ["Farstcoin","2322"], ["HEROcoin","2323"], ["BigONE Token","2324"], ["Matryx","2325"], ["Madcoin","2326"], ["Olympus Labs","2327"], ["Hyper Pay","2329"], ["Pylon Network","2330"], ["ENTCash","2331"], ["STRAKS","2332"], ["FidentiaX","2333"], ["BitClave","2334"], ["Lightning Bitcoin","2335"], ["Game.com","2336"], ["Lamden","2337"], ["Escroco","2338"], ["Bloom","2340"], ["SwftCoin","2341"], ["Covesting","2342"], ["CanYaCoin","2343"], ["AppCoins","2344"], ["High Performance Blockchain","2345"], ["WaykiChain","2346"], ["NumusCash","2347"], ["Measurable Data Token","2348"], ["Mixin","2349"], ["GameChain System","2350"], ["Numus","2351"], ["Coinlancer","2352"], ["CryptopiaFeeShares","2353"], ["GET Protocol","2354"], ["OP Coin","2355"], ["CFun","2356"], ["AI Doctor","2357"], ["Content and AD Network","2358"], ["Polis","2359"], ["Hacken","2360"], ["Show","2361"], ["Steneum Coin","2362"], ["Zap","2363"], ["TokenClub","2364"], ["FairGame","2366"], ["Aigang","2367"], ["REBL","2368"], ["INS Ecosystem","2369"], ["Bitcoin God","2370"], ["United Traders Token","2371"], ["Commodity Ad Network","2372"], ["Trade Token","2373"], ["BitDegree","2374"], ["QunQun","2375"], ["TopChain","2376"], ["Leverj","2377"], ["Karma","2378"], ["Kcash","2379"], ["ATN","2380"], ["Spectre.ai Dividend Token","2381"], ["Spectre.ai Utility Token","2382"], ["Jingtum Tech","2383"], ["Vezt","2384"], ["Cloud","2385"], ["United Crypto Community","2386"], ["Bitcoin Atom","2387"], ["ugChain","2389"], ["Bankex","2390"], ["EchoLink","2391"], ["Bottos","2392"], ["Telcoin","2394"], ["Ignition","2395"], ["WETH","2396"], ["Hackspace Capital","2397"], ["Selfkey","2398"], ["Internet Node Token","2399"], ["OneRoot Network","2400"], ["Global Jobcoin","2401"], ["Sense","2402"], ["MOAC","2403"], ["TOKYO","2404"], ["IOST","2405"], ["InvestDigital","2406"], ["AICHAIN","2407"], ["Qube","2408"], ["EtherDelta Token","2409"], ["SpaceChain","2410"], ["Galactrum","2411"], ["Harvest Masternode Coin","2412"], ["Ethorse","2413"], ["RealChain","2414"], ["ArbitrageCT","2415"], ["Theta Token","2416"], ["Maverick Chain","2418"], ["Profile Utility Token","2419"], ["Nitro","2420"], ["InsurePal","2421"], ["IDEX Membership","2422"], ["Aurora DAO","2423"], ["SingularityNET","2424"], ["Gatcoin","2425"], ["ShareX","2426"], ["ChatCoin","2427"], ["Scry.info","2428"], ["Mobius","2429"], ["Hydro Protocol","2430"], ["StarChain","2432"], ["IPChain","2433"], ["Maggie","2434"], ["LightChain","2435"], ["RefToken","2436"], ["YEE","2437"], ["Acute Angle Cloud","2438"], ["SelfSell","2439"], ["Read","2440"], ["Molecular Future","2441"], ["Trinity Network Credit","2443"], ["CRYPTO20","2444"], ["Block Array","2445"], ["DATA","2446"], ["Crypterium","2447"], ["Sparks","2448"], ["carVertical","2450"], ["Tokenbox","2452"], ["EDUCare","2453"], ["UnlimitedIP","2454"], ["PressOne","2455"], ["OFCOIN","2456"], ["True Chain","2457"], ["Odyssey","2458"], ["indaHash","2459"], ["Qbic","2460"], ["Peerguess","2461"], ["AidCoin","2462"], ["Devery","2464"], ["Blockport","2465"], ["aXpire","2466"], ["OriginTrail","2467"], ["LinkEye","2468"], ["Zilliqa","2469"], ["CoinMeet","2470"], ["Smartlands","2471"], ["Fortuna","2472"], ["All Sports","2473"], ["Matrix AI Network","2474"], ["Garlicoin","2475"], ["Ruff","2476"], ["Nework","2477"], ["CoinFi","2478"], ["Equal","2479"], ["HalalChain","2480"], ["Zeepin","2481"], ["CPChain","2482"], ["OceanChain","2483"], ["Hi Mutual Society","2484"], ["Candy","2485"], ["Speed Mining Service","2486"], ["Electronic PK Chain","2487"], ["ValueChain","2488"], ["BitWhite","2489"], ["CargoX","2490"], ["Travelflex","2491"], ["Elastos","2492"], ["STK","2493"], ["Pareto Network","2495"], ["Polymath","2496"], ["Medicalchain","2497"], ["Jibrel Network","2498"], ["SwissBorg","2499"], ["Zilla","2500"], ["adbank","2501"], ["Huobi Token","2502"], ["DMarket","2503"], ["Iungo","2504"], ["Bluzelle","2505"], ["Swarm","2506"], ["THEKEY","2507"], ["DCORP Utility","2508"], ["EtherSportz","2509"], ["Datawallet","2510"], ["WePower","2511"], ["U.CASH","2512"], ["GoldMint","2513"], ["Shekel","2514"], ["ACChain","2515"], ["MktCoin","2516"], ["Animation Vision Cash","2517"], ["LOCIcoin","2518"], ["Indicoin","2519"], ["Jesus Coin","2520"], ["BioCoin","2521"], ["Superior Coin","2522"], ["Tigereum","2523"], ["Universa","2524"], ["Alphacat","2525"], ["Envion","2526"], ["SureRemit","2527"], ["Dether","2528"], ["Cashaa","2529"], ["Fusion","2530"], ["W3Coin","2531"], ["Etherecash","2532"], ["Restart Energy MWAT","2533"], ["Gladius Token","2534"], ["DADI","2535"], ["Neurotoken","2536"], ["Gems ","2537"], ["Nectar","2538"], ["Republic Protocol","2539"], ["Litecoin Cash","2540"], ["Storiqa","2541"], ["Tidex Token","2542"], ["COPYTRACK","2543"], ["Nucleus Vision","2544"], ["Arcblock","2545"], ["Remme","2546"], ["Experty","2547"], ["POA Network","2548"], ["Ink Protocol","2549"], ["Rock","2550"], ["Bezop","2551"], ["IHT Real Estate Protocol","2552"], ["Refereum","2553"], ["Lympo","2554"], ["Sether","2555"], ["Credits","2556"], ["Bee Token","2557"], ["Insights Network","2558"], ["Cube","2559"], ["EZToken","2560"], ["BitTube","2561"], ["Education Ecosystem","2562"], ["TrueUSD","2563"], ["HOQU","2564"], ["StarterCoin","2565"], ["Ontology","2566"], ["DATx","2567"], ["JET8","2568"], ["CoinPoker","2569"], ["TomoChain","2570"], ["Graft","2571"], ["BABB","2572"], ["Electrify.Asia","2573"], ["Bitcoin Private","2575"], ["Tokenomy","2576"], ["Ravencoin","2577"], ["TE-FOOD","2578"], ["ShipChain","2579"], ["Leadcoin","2580"], ["Sharpe Platform Token","2581"], ["LALA World","2582"], ["Octoin Coin","2583"], ["Debitum","2584"], ["Centrality","2585"], ["Havven","2586"], ["Fluz Fluz","2587"], ["Loom Network","2588"], ["Guaranteed Ethurance Token Extra","2589"], ["HireMatch","2590"], ["Dropil","2591"], ["Banca","2592"], ["Dragon Coins","2593"], ["LatiumX","2594"], ["NANJCOIN","2595"], ["CK USD","2596"], ["UpToken","2597"], ["Banyan Network","2598"], ["Noah Coin","2599"], ["LGO Exchange","2600"], ["1World","2601"], ["NaPoleonX","2602"], ["Pundi X","2603"], ["Bitcoin Green","2604"], ["BnkToTheFuture","2605"], ["Wanchain","2606"], ["AMLT","2607"], ["Mithril","2608"], ["Lendroid Support Token","2609"], ["Peculium","2610"], ["Spectiv","2611"], ["BitRent","2612"], ["BlitzPredict","2614"], ["Blocklancer","2615"], ["Stipend","2616"], ["IP Exchange","2617"], ["StockChain","2618"], ["BitStation","2619"], ["Switcheo","2620"], ["Consensus","2621"], ["Sentinel Chain","2624"], ["Vice Industry Token","2625"], ["Friendz","2626"], ["TokenPay","2627"], ["Rentberry","2628"], ["Stellite","2629"], ["PolySwarm","2630"], ["ODEM","2631"], ["Monero Original","2632"], ["Stakenet","2633"], ["XinFin Network","2634"], ["TokenDesk","2635"], ["BelugaPay","2636"], ["Fidelium","2637"], ["Cortex","2638"], ["Arbitracoin","2639"], ["WCOIN","2640"], ["Apex","2641"], ["CyberVein","2642"], ["Sentinel","2643"], ["eosDAC","2644"], ["U Network","2645"], ["AdHive","2646"], ["SnipCoin","2647"], ["Bitsum","2648"], ["DeviantCoin","2649"], ["CommerceBlock","2650"], ["GreenMed","2651"], ["Curriculum Vitae","2652"], ["Auctus","2653"], ["Budbo","2654"], ["Monero Classic","2655"], ["Daneel","2656"], ["BrahmaOS","2657"], ["SyncFab","2658"], ["Dignity","2659"], ["Aditus","2660"], ["Tripio","2661"], ["Haven Protocol","2662"], ["StarCoin","2663"], ["CryCash","2664"], ["Dero","2665"], ["Effect.AI","2666"], ["FintruX Network","2667"], ["Earth Token","2668"], ["MARK.SPACE","2669"], ["Pixie Coin","2670"], ["Cropcoin","2671"], ["SRCOIN","2672"], ["Chainium","2673"], ["Masari","2674"], ["Dock","2675"], ["PHI Token","2676"], ["Linker Coin","2677"], ["TraDove B2BCoin","2678"], ["Decentralized Machine Learning","2679"], ["Helbiz","2680"], ["Origami","2681"], ["Holo","2682"], ["TrakInvest","2683"], ["Aphelion","2684"], ["Zebi","2685"], ["Lendingblock","2686"], ["Proxeus","2687"], ["Vipstar Coin","2688"], ["Rublix","2689"], ["Biotron","2690"], ["Penta","2691"], ["Nebula AI","2692"], ["Loopring [NEO]","2693"], ["Nexo","2694"], ["VeriME","2695"], ["DAEX","2696"], ["Hydrogen","2698"], ["Sharder","2699"], ["TrustNote","2701"], ["Bitcoin Interest","2702"], ["BetterBetting","2703"], ["Transcodium","2704"], ["Amon","2705"], ["Plancoin","2706"], ["Crowd Machine","2708"], ["Morpheus Labs","2709"], ["Live Stars","2710"], ["Docademic","2711"], ["MyToken","2712"], ["KEY","2713"], ["Nexty","2714"], ["ConnectJob","2715"], ["BoutsPro","2717"], ["PolicyPal Network","2718"], ["Cybereits","2719"], ["Parkgene","2720"], ["APR Coin","2721"], ["AC3","2722"], ["FuzeX","2723"], ["Zippie","2724"], ["Skrumble Network","2725"], ["DAOstack","2726"], ["Bezant","2727"], ["TokenStars","2729"], ["Fitrova","2730"], ["Utrum","2731"], ["Aston","2732"], ["Freyrchain","2733"], ["EduCoin","2734"], ["Content Neutrality Network","2735"], ["InsurChain","2736"], ["Global Social Chain","2737"], ["Super Game Chain","2738"], ["Digix Gold Token","2739"], ["Influence Chain","2740"], ["Intelligent Investment Chain","2741"], ["Sakura Bloom","2742"], ["Bank Coin","2743"], ["NPER","2744"], ["Dascoin","2746"], ["BlockMesh","2747"], ["Loki","2748"], ["Signals Network","2749"], ["Oyster Shell","2750"], ["FundRequest","2751"], ["Datarius Credit","2752"], ["Colu Local Network","2753"], ["HeroNode","2754"], ["Hero","2755"], ["Tokia","2756"], ["Callisto Network","2757"], ["Unibright","2758"], ["Patron","2759"], ["Libra Credit","2760"], ["Local World Forwarders","2761"], ["Open Platform","2762"], ["Morpheus Network","2763"], ["Silent Notary","2764"], ["XYO Network","2765"], ["Cryptaur","2766"], ["APIS","2767"], ["Fabric Token","2768"], ["Rhenium","2769"], ["Cazcoin","2770"], ["RED","2771"], ["Digitex Futures","2772"], ["GINcoin","2773"], ["Invacio","2774"], ["Faceter","2775"], ["Travala","2776"], ["IoTeX","2777"], ["Eximchain","2778"], ["Level Up Coin","2779"], ["NKN","2780"], ["Naviaddress","2825"], ["ZIP","2826"], ["Phantasma","2827"], ["SPINDLE","2828"], ["REPO","2829"], ["Seele","2830"], ["EJOY","2831"], ["Ivy","2833"], ["ContractNet","2834"], ["Endor Protocol","2835"], ["Bigbom","2836"], ["0xBitcoin","2837"], ["PCHAIN","2838"], ["Shopin","2839"], ["QuarkChain","2840"], ["LoyalCoin","2841"], ["Bankera","2842"], ["Ether Zero","2843"], ["Shivom","2844"], ["MEDX","2845"], ["FuturoCoin","2846"], ["The Abyss","2847"], ["Paymon","2848"], ["Hurify","2849"], ["TRAXIA","2850"], ["CGC Token","2851"], ["Engine","2852"], ["MIRQ","2853"], ["PikcioChain","2854"], ["CashBet Coin","2855"], ["CEEK VR","2856"], ["SalPay","2857"], ["Couchain","2858"], ["XMax","2859"], ["GoChain","2861"], ["Smartshare","2862"]]
autocomplete(document.getElementById("bcoin"), arr);
autocomplete(document.getElementById("scoin"), arr);
</script>
</body>
</html>
<?php } else {?>
	<?php 
		echo "<script>alert('Please Login');</script>";
		echo "<script>window.location = 'index.php';</script>";
	?>
<?php } ?>
