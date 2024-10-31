<?php

if ( !defined('ABSPATH') ) {
	die;
}

?>

<h3>De shortcode gebruiken</h3>
<div class="suf-section fix">
<strong>Beschikbare Slugs</strong><br />
Bijv. [nevobo feed="Link.rss" aantal="3" highlight_color="#000000" icon_color="#FF0000" css_style="" sporthal="1" plaats="1" ical="1" nevobo_maps="1"]<br />
<ul style="list-style:initial;padding-left:20px;">
	<li>[feed]: Het is noodzakelijk om een correcte RSS URL in te voeren. Voor meer informatie welke URL\'s mogelijk zijn zie "Nevobo RSS Feeds".</li>
	<li>[css_style]: Het is mogelijk om de tabel van stijl te veranderen. Bijv. "table-dark table-hover table-striped". Voor meer informatie: https://getbootstrap.com/docs/4.3/content/tables/
		<ul style="list-style:initial;padding-left:20px;">
		<li>table-sm</li>
		<li>table-bordered</li>
		<li>table-striped</li>
		<li>table-hover</li>
		<li>table-inverse</li>
		<li>table-dark</li>
		<li>table-diy</li>
		</ul>
	</li>
	<li>[vereniging]: Voer hier jouw verenigingsnaam in. Deze zal gebruikt worden om een accentkleur aan jouw vereniging toe te voegen.</li>
	<li>[highlight_color]: De accentkleur van de verenigingsnaam.</li>
	<li>[icon_color]: De accentkleur voor de gebruikte iconen.</li>
	<li>[aantal]: Het maximaal aantal te tonen regels.</li>
	<li>[sporthal]: Sporthalnaam tonen.</li>
	<li>[plaats]: Plaatsnaam tonen.</li>
	<li>[ical]: Agenda (ical) link tonen.</li>
	<li>[nevobo_maps]: Google Maps link tonen.</li>
	<li>[setpunten]: Bij de stand de gespeelde sets en punten tonen.</li>
	<li>[sets]: Bij uitslagen de setpunten tonen.</li>
</ul>
</div>
<h3>Nevobo RSS Feeds</h3>
<div class="suf-section fix">
<strong>De URL’s met ondersteuning</strong>
<ul style="list-style:initial;padding-left:20px;">
	<li>https://api.nevobo.nl/export/poule/{regio}/{poule}/programma.rss</li>
	<li>https://api.nevobo.nl/export/poule/{regio}/{poule}/resultaten.rss</li>
	<li>https://api.nevobo.nl/export/poule/{regio}/{poule}/stand.rss</li>
	<li>https://api.nevobo.nl/export/vereniging/{verenigingscode}/programma.rss</li>
	<li>https://api.nevobo.nl/export/vereniging/{verenigingscode}/resultaten.rss</li>
	<li>https://api.nevobo.nl/export/team/{verenigingscode}/{teamtype}/{volgnummer}/programma.rss</li>
	<li>https://api.nevobo.nl/export/team/{verenigingscode}/{teamtype}/{volgnummer}/resultaten.rss</li>
	<li>https://api.nevobo.nl/export/sporthal/{sporthal}/programma.rss</li>
	<li>https://api.nevobo.nl/export/sporthal/{sporthal}/resultaten.rss</li>
</ul>
{regio} = ‘regio-noord’, ‘regio-oost’, ‘regio-zuid’, ‘regio-west’, ‘nationale-competitie’, ‘kampioenschappen’<br />
{poule} = pouleafkorting<br />
{teamtype} = ‘dames-senioren’, ‘jongens-a’, etc.<br />
{sporthal} = sporthalcode (vijf letters)
<br />
Op de Nevobo website staat rechtsonderin "Programma Exporteren". Daar kan gemakkelijk de RSS Feed URL gevonden worden.<br />
</div>
<h3>PHP Tijdcode</h3>
<div class="suf-section fix">
<strong>Klein overzicht:</strong>
<ul style="list-style:initial;padding-left:20px;">
	<li>d F H:i - 27 September 21:30</li>
</ul>
Voor meer informatie https://www.php.net/manual/en/function.date.php#refsect1-function.date-parameters (Engels).<br />
</div>
<h3>Donatie</h3>
<div class="suf-section fix">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick" />
	<input type="hidden" name="hosted_button_id" value="FFYL27WAJ4DY4" />
	<input type="image" src="https://www.paypalobjects.com/nl_NL/NL/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Doneren met PayPal-knop" />
	<img alt="" border="0" src="https://www.paypal.com/nl_NL/i/scr/pixel.gif" width="1" height="1" />
</form>
</div>