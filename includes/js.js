var aantal_landen = 42;
var c;
var ctx;
var canvas_width = 2000;
var canvas_height = 970;
var i;
var possibleAttTargets;
var hoeveelheidmannenExploded;
var eigenaarExploded;
var personal_game_id;
var versterking;


$( document ).ready(function() {
	setup_arrays();
    draw_countries();
	place_owned_countries_in_dock();
	place_target_countries_in_div();
	c=document.getElementById("gamecanvas");
	ctx=c.getContext("2d");
});

function setup_arrays(){
	hoeveelheidmannenExploded = document.getElementById('hoeveelheidmannenString').value.split(';');
	eigenaarExploded = document.getElementById('eigenaarString').value.split(';');
	personal_game_id = document.getElementById('personal_game_id').value;
	versterking = document.getElementById('versterking').value;

	i = 0;
	possibleAttTargets = new Array();
	while (i < aantal_landen){
		possibleAttTargets[i] = document.getElementById(i + 'pAT').value.split(';');
		i++;
	}
	i = 0;
	countryNames = new Array();
	while (i < aantal_landen){
		countryNames[i] = document.getElementById(i).value;
		i++;
	}

	console.log(hoeveelheidmannenExploded);
	console.log(eigenaarExploded);
	console.log(possibleAttTargets);
	console.log(countryNames);
	//setup de 3 arrays, hoeveelheidmannen, eigenaar, possAttTargets.
}

/* WEIRD MATH SHIT */
function redraw(){
	clear_canvas();
	if (typeof sxy !== 'undefined' && typeof txy !== 'undefined') {
		draw_line(sxy[0], sxy[1], txy[0], txy[1]);
	}
	draw_countries();
}

function draw_countries(){
	var i = 0;
	c=document.getElementById("gamecanvas");
	ctx=c.getContext("2d");
	while (i < aantal_landen){
		draw_gameboard(i);
		i++;
	}
}

function draw_gameboard(id){
	//tussenstap haalt x, en y op en geeft die door aan drawarmysize
	//tussenstap haalt x, en y op en geeft die door aan drawarmysize

	var x = +document.getElementById(id + "X").value;
	var y = +document.getElementById(id + "Y").value;
	draw_army_size(x, y, id);		
}

function clear_canvas(){
	//leegt de canvas
	var c=document.getElementById("gamecanvas");
	var ctx=c.getContext("2d");
	ctx.clearRect(0, 0, canvas_width, canvas_height);
}

function draw_army_size(x, y, id){
	//functie die verschillende functies aanroept om een land in zijn geheel te renderen.
	//aka rondje met hoeveelheidmannen daaring, gekleurt naar de speler die hem bezit.
	//Vierkantje daarboven met daarin de naam van het land ook gekleurt naar de speler die hem bezit.

	var size = hoeveelheidmannenExploded[id];
	var owner = eigenaarExploded[id];

	//doet de kleuren voor alle 8 spelers.
	var bordercolor;
	if (owner == 0) {
		bordercolor = 'yellow';
	}else if(owner == 1){
		bordercolor = 'darkblue';
	}else if(owner == 2){
		bordercolor = 'darkgreen';
	}else if(owner == 3){
		bordercolor = 'purple';
	}else if(owner == 4){
		bordercolor = 'lightgreen';
	}else if(owner == 5){
		bordercolor = 'lightblue';
	}else if(owner == 6){
		bordercolor = 'pink';
	}else if(owner == 7){
		bordercolor = 'gray';
	}

    draw_circle((x+40), (y+40), size, bordercolor);

    country = document.getElementById(id).value;
	var metrics = ctx.measureText(country);
	var width = metrics.width;
    ctx.font = 'bold 12pt Arial';
    ctx.textAlign = 'center';


	ctx.beginPath();
	round_rect(ctx, (x-(width/2)+45), (y-4), (width-10), 16, 5, 'black', bordercolor);
	ctx.fillStyle = 'white';
	ctx.fillText(country, (x+40), (y+10));
	ctx.closePath();
}

function round_rect(ctx, x, y, width, height, radius, fill, stroke) {
	//maakt een gerond vierkantje op de x, y positie.
	//magic
	if (typeof stroke == "undefined" ) {
	    stroke = true;
	}
	
	if (typeof radius === "undefined") {
	    radius = 5;
	}
	
	ctx.fillStyle = fill;
	ctx.strokeStyle = stroke;
	ctx.beginPath();
		ctx.moveTo(x + radius, y);
		ctx.lineTo(x + width - radius, y);
		ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
		ctx.lineTo(x + width, y + height - radius);
		ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
		ctx.lineTo(x + radius, y + height);
		ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
		ctx.lineTo(x, y + radius);
		ctx.quadraticCurveTo(x, y, x + radius, y);
	ctx.closePath();
	
	if (stroke) {
		ctx.strokeStyle = 'black';
		ctx.lineWidth = '12'
		ctx.stroke();
		ctx.strokeStyle = stroke;
		ctx.lineWidth = '6'
		ctx.stroke();
	}
	if (fill) {
	    ctx.fill();
	}        
}	

function draw_line(x, y, tx, ty){
	// x = x positie source, y = y positie source.
	// tx = x positie target, ty = y positie target.
	//magic
	
	//maakt een lijntje tussen 2 punten met een rondje op het begin en het eind.
	var c=document.getElementById("gamecanvas");
	var ctx=c.getContext("2d");
	x = x+40;
	y = y+40;
	tx = tx+40;
	ty = ty+40;
	var angle = Math.atan2(ty-y,tx-x);
	ctx.lineWidth = 12;
	ctx.strokeStyle = 'black';
	ctx.fillStyle = 'black';
	var headlen = 70;

	//maakt een rondje om het sourceland en de arrow.
    ctx.beginPath();
		ctx.arc(x, y, 23, 0,(2 * Math.PI), false);
	    ctx.moveTo(tx, ty);
	    ctx.lineTo(tx-headlen*Math.cos(angle-Math.PI/6), ty-headlen*Math.sin(angle-Math.PI/6));
	    ctx.moveTo(tx, ty);
	    ctx.lineTo(tx-headlen*Math.cos(angle+Math.PI/6), ty-headlen*Math.sin(angle+Math.PI/6));
    	ctx.moveTo(x, y);
    	ctx.lineTo(tx, ty);
    	ctx.stroke();
    	ctx.fillStyle = 'red';
    	ctx.arc(tx, ty, 1, 0,(2 * Math.PI), false);
    	ctx.fill();
    ctx.closePath();

    ctx.beginPath();
		ctx.arc(tx, ty, 34, 0,(2 * Math.PI), false);				    	
    	ctx.fill();
    ctx.closePath();

    ctx.lineWidth = 6;
	ctx.strokeStyle = 'red';
	ctx.fillStyle = 'red';
	var headlen = 65;

	//maakt een rondje om het targetland en de 'kleinere' arrow in de andere arrow
    ctx.beginPath();
		ctx.arc(x, y, 1, 0,(2 * Math.PI), false);
	    ctx.moveTo(tx, ty);
	    ctx.lineTo(tx-headlen*Math.cos(angle-Math.PI/6),ty-headlen*Math.sin(angle-Math.PI/6));
	    ctx.moveTo(tx, ty);
	    ctx.lineTo(tx-headlen*Math.cos(angle+Math.PI/6),ty-headlen*Math.sin(angle+Math.PI/6));
    	ctx.moveTo(x, y);
    	ctx.lineTo(tx, ty);
    	ctx.stroke();
    ctx.closePath();
}

function draw_circle(x, y, AM, bordercolor, size){
	//magic
	if (typeof size == "undefined" ) {
	    size = 20;
	}

	var c=document.getElementById("gamecanvas");
	var ctx=c.getContext("2d");

	ctx.font = '20pt Sans-Serif';
    ctx.lineWidth = 10;
    ctx.fillStyle = 'black';
    ctx.textAlign = 'center';

    //maakt een rondje met border
    ctx.beginPath();
    	ctx.arc(x, y, 20, 0,(2 * Math.PI), false);
    	ctx.strokeStyle = 'black';
    	ctx.lineWidth = 15;
    	ctx.stroke();
    	ctx.strokeStyle = bordercolor;
    	ctx.lineWidth = 10;
    	ctx.stroke();
    	ctx.fill();

    	//plaatst het aantal aanwezige mannen in het rondje.
    	ctx.fillStyle = 'white';
    	ctx.fillText(AM, (x-1), (y+10));
    ctx.closePath();
}

function scroll_screen(country){
	//scrollt de div waarin de canvas zit zo dicht mogelijk naar de offset van het land.
	var h = +document.getElementById('gamecont').offsetHeight;
	var w = +document.getElementById('gamecont').offsetWidth;
	//haalt de offset van de container waarin de canvas zit.(hoe groot het divje is waarin de canvas zit)

	var x = +document.getElementById(country + "X").value;
	x=(x-(w/2)+40);
	//berekent  de x en de y positie waarnaaroe die moet scrollen
	var y = +document.getElementById(country + "Y").value;
	y=(y-(h/2)+40);

	$('#gamecont').animate({
		scrollLeft: x,
		scrollTop: y
	}, 250);
}

function get_position(country){
	var x = +document.getElementById(country + "X").value;
	var y = +document.getElementById(country + "Y").value;
	return [x, y];
}

function place_owned_countries_in_dock(){
	i = 0;
	while(i < aantal_landen){
		if (eigenaarExploded[i] == personal_game_id) {
			var country_name = document.getElementById(i).value;
			var html = '<button style="border: 0; outline: 0; background: none; padding: 0; margin: 0;"><div onclick="change_army_size(\'' + i + '\')" id="button_' + i + '" style="height: 50px; border-right: 2px solid black; border-left: 2px solid black; font-size: 25px; line-height: 48px; background-color: white; display: table-cell; padding-left: 15px; padding-right: 15px; border-top: 1px solid black; border-bottom: 1px solid black;">' + country_name + '</div></button>';
			document.getElementById('countries_div').innerHTML += html;
		}
		i++;
	}
}

function place_target_countries_in_div(){
	i = 0;
	while(i < aantal_landen){
		possibleAttTargets_i = 0;
		while (possibleAttTargets[i].length > possibleAttTargets_i){
			temp = possibleAttTargets[i][possibleAttTargets_i];
			if (eigenaarExploded[temp] != personal_game_id) {
				var html = '<button onclick="select_target_country(\'' + temp + '\');" style="border: 0; outline: 0; background: none; padding: 0; margin: 0;"><div style="height: 50px; border-right: 2px solid black; border-left: 2px solid black; font-size: 25px; line-height: 48px; background-color: red; display: table-cell; padding-left: 15px; padding-right: 15px; border-top: 1px solid black; border-bottom: 1px solid black;">' + countryNames[temp] + '</div></button>';
				document.getElementById('target_div_' + i).innerHTML += html;
			}else{
				var html = '<button onclick="select_target_country(\'' + temp + '\');" style="border: 0; outline: 0; background: none; padding: 0; margin: 0;"><div style="height: 50px; border-right: 2px solid black; border-left: 2px solid black; font-size: 25px; line-height: 48px; background-color: blue; display: table-cell; padding-left: 15px; padding-right: 15px; border-top: 1px solid black; border-bottom: 1px solid black;">' + countryNames[temp] + '</div></button>';
				document.getElementById('target_div_' + i).innerHTML += html;
			}
			possibleAttTargets_i++;
		}
		i++;
	}
}

function change_army_size(id){	//in button
	if (document.getElementById('versterking').value > 0) {
		hoeveelheidmannenExploded[id]++;
		scroll_screen(id);
		versterking--;
		document.getElementById("versterking").value = document.getElementById("versterking").value-1;
		//document.getElementById("aantal_mannen_stats").innerHTML = document.getElementById("versterking").value;
		//nieuwe UI maken
		redraw();
		document.getElementById('addMen').innerHTML += '<input type="hidden" value="' + id + '" name="addMen' + versterking + '" id="addMen' + versterking + '">';
	}else{
		select_source_country(id);
	}
}

function select_source_country(id){
	var hm = hoeveelheidmannenExploded[id];
	hm = hm - 2;

	document.getElementById('source_div_ui').innerHTML = 'Source: ' + countryNames[id];
	document.getElementById('hm_div_ui').innerHTML = '<input type="number" id="sourceLandAMHoevMannInput" value="0" min="0" max="' + hm + '" style="text-align: center; width: 35px;">';
	//hm ding zetten ook
	document.getElementById('countries_div').innerHTML = document.getElementById('target_div_' + id).innerHTML;
	document.getElementById('sourceLand').value = id;
}

var sxy;
var txy;
function select_target_country(id){
	document.getElementById('targetLand').value = id;
	document.getElementById('sourceLandAMHoevMann').value = '';
	document.getElementById('target_div_ui').innerHTML = 'Target: ' + countryNames[id];

	sxy = get_position(document.getElementById('sourceLand').value);
	txy = get_position(id);
	redraw();
	scroll_screen(id);
}

function submit(){
	if (document.getElementById('sourceLand').value != '99' && document.getElementById('targetLand').value != '99') {
		document.getElementById('sourceLandAMHoevMann').value = document.getElementById('sourceLandAMHoevMannInput').value;
	};
	document.getElementById('zet_form').submit();
}

function reset(){
	document.getElementById('targetLand').value = '99';
	document.getElementById('sourceLand').value = '99';
	document.getElementById('sourceLandAMHoevMann').value = '99';
	document.getElementById('countries_div').innerHTML = '';
	document.getElementById('target_div_ui').innerHTML = 'Target: target';
	document.getElementById('source_div_ui').innerHTML = 'Source: source';
	document.getElementById('hm_div_ui').innerHTML = 'Hoeveelheidmannen';
	sxy = undefined;
	txy = undefined;
	redraw();
	place_owned_countries_in_dock();
}


/*
geen source/target als dat kan
plaatsen tot
eigen functies weer:
submit
select_source_country
select_target_country
change_army_size
en UI functies
Reset source country knop
Input voor hoeveelheid aanval mannen etc
knop voor submit
*/