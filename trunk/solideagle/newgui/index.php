<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Gebruikersbeheer</title>
	<link rel="stylesheet" href="css/ui-custom/jquery-ui-1.8.18.custom.css">
	<link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style2.css">
        <link rel="stylesheet" href="css/forms.css">
	<link rel="stylesheet" href="css/uniform.default.css">
	<script src="js/jquery-1.7.1.min.js"></script>
	<script src="js/jquery-ui-1.8.18.custom.min.js"></script>
	<script src="js/jquery.easytabs.min.js"></script>
	<script src="js/jquery.uniform.min.js"></script>

	<script>
	$(function() {
		$( "#tabs" ).easytabs({cache: false});

		$("#tabs").bind('easytabs:ajax:complete', function(e, clicked, panel, response, status, xhr) {
	     	$(panel).trigger('easytabs:ajax:complete', response);
	    });
            
            $( "#btnUser" ).button({
                icons: {
                    primary: "ui-icon-locked"
                },
                text: false
            });
                
            $("#tabs2").easytabs({uiTabs: true});


            $("#testtab").bind('easytabs:ajax:complete',function(content){

                            $.getScript("controllers/course.php?q=js");
            });

 
        updateUniform();
        
        
	});


	function updateUniform()
	{
		   $("select, input:checkbox, input:radio, input:file").uniform();
	}
	</script>
</head>
<body>



<div id="userbar">
    <a href="#" id="lnkUsername">gebruikersnaam</a>	
    <button id="btnUser">user button</button>
</div>

<div id="header">
	<span>Gebruikersbeheer</span>
	<img src="images/logo.png" height="90px" />
</div>

<div id="content">

<div id="tabs">
	
	<ul>
		<li><a href="#dashboard">Dashboard</a></li>
		<li><a href="#groepengebruikers">Groepen en gebruikers</a></li>
		<li><a href="#configuratie">Configuratie</a></li>
		<li><a href="controllers/person.php?q=content #addPerson"  data-target="#testtab">Test</a></li>
	</ul>
	<div id="dashboard">
		<p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
	</div>
	<div id="groepengebruikers">
		<div id="tabs2">
				<ul>
					<li><a href="#zoeken">Zoeken</a></li>
					<li><a href="#partymode">Partymode!</a></li>
				</ul>
				<div id="zoeken">
				<p> 4044444444444</p>
				</div>
				<div id="partymode"><p> EYO EYO EYO</p></div>
		</div>
	</div>
	<div id="configuratie">
		<p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
		<p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
	</div>
	
		
	<div id="testtab">
		
	</div>
	
</div>

</div><!-- End demo -->




</body>
</html>
