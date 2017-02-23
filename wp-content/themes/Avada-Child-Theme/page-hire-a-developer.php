<!DOCTYPE html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<style>

	@import url('https://fonts.googleapis.com/css?family=Roboto:300,400');
	body{background-color:#8edbff;margin:0;}
	h1,h2{color:#000;font-family: 'Roboto', sans-serif;}
	h1{font-size:38px;font-weight:300;}
	a,p{font-family:'Roboto', sans-serif;}
	h2{font-size:26px;font-weight:400;}
	*{box-sizing:border-box;}
	.main-wrap{width:960px;position:relative;margin:0 auto;max-width:90%;min-width:55%;}
	.clearfix:before, &:after{content: "";display:table;} 
	.clearfix:after{clear:both;}
	svg{fill:#000;}

	.form-head{width:100%;position:relative;height:65px;background-color:#fff;text-align:center;box-shadow:0px 10px 50px -2px rgba(0, 0, 0, 0.14);}
	.form-head svg{fill:#43A3D8;width:57px;height:33px;position:relative;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);}
	.form-head a{transition:all .2s ease-in-out;}
	.form-head a:hover{opacity:.8;}
	.progress-bar{width: 100%;height: 5px;position: absolute;bottom: 0;background: -webkit-gradient(linear, left top, right top, color-stop(25%,#48A4D6), color-stop(40%,#00F));background: -moz-linear-gradient(left center, #48A4D6 25%, #00F 25%);background: -o-linear-gradient(left, #48A4D6 25%, #00F 25%);background: linear-gradient(to right, #48A4D6 25%, #fff 25%);}
	.progress-bar.two{background: -webkit-gradient(linear, left top, right top, color-stop(50%,#48A4D6), color-stop(50%,#00F));background: -moz-linear-gradient(left center, #48A4D6 50%, #00F 50%);background: -o-linear-gradient(left, #48A4D6 50%, #00F 50%);background: linear-gradient(to right, #48A4D6 50%, #fff 50%);}
	.progress-bar.three{background: -webkit-gradient(linear, left top, right top, color-stop(75%,#48A4D6), color-stop(75%,#00F));background: -moz-linear-gradient(left center, #48A4D6 75%, #00F 75%);background: -o-linear-gradient(left, #48A4D6 75%, #00F 75%);background: linear-gradient(to right, #48A4D6 75%, #fff 75%);}
	.progress-bar.four{background: -webkit-gradient(linear, left top, right top, color-stop(100%,#48A4D6), color-stop(100%,#00F));background: -moz-linear-gradient(left center, #48A4D6 100%, #00F 100%);background: -o-linear-gradient(left, #48A4D6 100%, #00F 100%);background: linear-gradient(to right, #48A4D6 100%, #fff 100%);}


	.screen{text-align:center;margin-top:110px;display:none;transition:transform .4s ease-in-out;}
	.screen.active{display:block;}
	.screen h1{max-width:80%;margin:15px auto 5px;}
	.screen svg, .screen h2{transition:all .1s ease-in-out;pointer-events:none;}
	.screen svg{height:90px;}

	.card svg{transform:scale(.9);}
	.card .inner:hover svg, .card .inner.active svg{fill:#43A3D8;transform:scale(1);}
	.card .inner:hover h2, .card .inner.active h2{color:#43A3D8;}
	.card h2{max-width:85%;margin:20px auto 0;}

	.inner{cursor:pointer;background-color:#fff;height:auto;padding:75px 0;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);transition:all .2s ease-in-out;}
	.inner:hover{box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);cursor:pointer;}
	
	#timeframe .card, #product-type .card{width:33%;float:left;padding:30px;}
	#remote-dev .card{width:50%;float:left;padding:30px;}
	#main-fields{background:white;max-width:75%;margin:100px auto;padding:50px 0;}
	#product-type .inner{min-height:397px;}

	input[type="submit"], a.continue{background-color:#48A4D6;border-radius:0;-webkit-appearance:none;border:none;padding:15px 45px;color:#fff;font-size:20px;cursor:pointer;transition:all .2s ease-in-out;}
	input[type="submit"]{display:none;}
	input[type="submit"]:hover{color:#48A4D6;background-color:#fff;}
	a.continue{display:inline-block;margin-top:20px;}
	a.continue:hover{opacity:.8;}

	/*#thank-you a{text-decoration:none;color:#48A4D6;}
	#thank-you a:hover{opacity:.8;}*/

	#main-fields input{margin-bottom:8px;width:65%;height:50px;background:#fff;border:none;padding:15px;-webkit-box-shadow: inset 0px 0px 10px -1px rgba(0,0,0,0.2);-moz-box-shadow: inset 0px 0px 12px -1px rgba(0,0,0,0.2);box-shadow: inset 0px 0px 12px -1px rgba(0,0,0,0.2);color:#000;font-size:16px;font-family: "Robot", sans-serif; font-weight: 300;}
	#main-fields input:focus {outline: none !important;border:1px solid #48A4D6;box-shadow: 0 0 10px #48A4D6;}
	#main-fields svg{margin:0 auto 10px;}
	::-webkit-input-placeholder{color:#BDBDBD;font-size:16px;font-family: "Robot", sans-serif; font-weight: 300;}
	::-moz-placeholder{color:#BDBDBD;font-size:16px;font-family: "Robot", sans-serif; font-weight: 300;}
	:-ms-input-placeholder{color:#BDBDBD;font-size:16px;font-family: "Robot", sans-serif; font-weight: 300;}
	:-moz-placeholder{color:#BDBDBD;font-size:16px;font-family: "Robot", sans-serif; font-weight: 300;}

	@media (max-width:767px) {
		h1{font-size:32px;}
		h2{font-size:22px;}

		.screen{margin-top:30px;}

		.card{width:100% !important;padding:15px 0 !important;}
		.screen .inner{padding:30px 0;}
		.screen svg{height:55px;}
		.screen h2{margin-top:15px;}
		.card h2{max-width:100%;}

		#main-fields{max-width:100%;padding:30px 20px;margin:30px auto;}
		#main-fields input{width:100%;}

		#product-type .inner{min-height:auto;}
	}

</style>

<!-- Google Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-84969870-1', 'auto');
  ga('send', 'pageview');
</script>
<!-- End GA -->

</head>

<div class="form-head">
	<a href="/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 57 33"><path d="M45,9.06,48,1.93A15.94,15.94,0,0,0,45.27.76L42.2,7.9A8.29,8.29,0,0,1,45,9.06Z"/><path d="M48.68,15.26c0,.25,0,.51,0,.77A8.34,8.34,0,1,1,40.4,7.69L43.73.36A16,16,0,1,0,56.43,16c0-.24,0-.48,0-.72H48.73Z"/><path d="M26.69,1.67V0H0V6.89H9.46V32.06H18V6.89h4.81A20,20,0,0,1,26.69,1.67Z"/></svg></a>
	<div class="progress-bar"></div>
</div><!--end form-head-->

<div class="form-body">
	
	<form id="dev-form" name="dev-form" action="https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST">
	<!-- <input type="hidden" name="debug" value="1"> -->
	<input type=hidden name="oid" value="00Di0000000gcqN">
	<input type=hidden name="retURL" value="http://talentcrank.com/hire-developer-thank-you">

	<div class="main-wrap">

		<div id="main-fields" class="screen active">
			<svg viewBox="0 0 103.25 103.25"><path d="M51.63,0a51.63,51.63,0,1,0,51.63,51.63A51.62,51.62,0,0,0,51.63,0Zm0,4.3A47.3,47.3,0,0,1,88,81.85C83.3,79.88,72.16,76,65.27,74c-.59-.19-.68-.21-.68-2.66a14.12,14.12,0,0,1,1.64-5.78,29.62,29.62,0,0,0,2.3-7.84c1-1.21,2.47-3.61,3.39-8.17.8-4,.43-5.49-.1-6.86-.06-.15-.11-.29-.16-.43a45,45,0,0,1,.76-9.65c.48-2.61-.12-8.16-3.72-12.75-2.27-2.9-6.61-6.46-14.54-7H49.8c-7.8.49-12.14,4.05-14.4,7C31.81,24.42,31.21,30,31.69,32.58a45.15,45.15,0,0,1,.77,9.63,3.09,3.09,0,0,1-.16.45c-.53,1.38-.91,2.84-.1,6.86.91,4.56,2.34,7,3.39,8.17a29.32,29.32,0,0,0,2.3,7.84,14,14,0,0,1,.94,5.87c0,2.45-.09,2.48-.64,2.65C31,76.16,19.7,80.26,15.47,82.11A47.29,47.29,0,0,1,51.63,4.3Zm-33,81.16c4.85-2,14.5-5.42,20.85-7.3,3.69-1.16,3.69-4.27,3.69-6.77a18.56,18.56,0,0,0-1.35-7.7,24.37,24.37,0,0,1-2-7.17,2.12,2.12,0,0,0-.73-1.43c-.61-.53-1.85-2.49-2.64-6.43-.63-3.12-.36-3.8-.1-4.46a8.83,8.83,0,0,0,.3-.87c.52-1.89-.06-8.09-.69-11.53a13.46,13.46,0,0,1,2.87-9.32c2.51-3.21,6.31-5,11.15-5.31H54c5,.31,8.77,2.1,11.29,5.31a13.44,13.44,0,0,1,2.86,9.33c-.62,3.44-1.2,9.64-.69,11.53.09.31.19.59.3.88.26.66.52,1.34-.1,4.46-.79,3.94-2,5.9-2.64,6.43a2.17,2.17,0,0,0-.73,1.43,24.22,24.22,0,0,1-2,7.17,18.27,18.27,0,0,0-2,7.61c0,2.5,0,5.6,3.73,6.78,6.07,1.8,15.78,5.13,20.9,7.14a47.22,47.22,0,0,1-66.32.23Z"></path></svg>
			<h2>What's the best way for us to reach you?</h2>
			<input id="first_name" class="initial-field" maxlength="40" name="first_name" size="20" type="text" required placeholder="First Name" /><br>
			<input id="last_name" class="initial-field" maxlength="80" name="last_name" size="20" type="text" required placeholder="Last Name" /><br>
			<input id="company" class="initial-field" maxlength="40" name="company" size="20" type="text" placeholder="Company" /><br>
			<input id="email" class="initial-field" maxlength="80" name="email" size="20" type="email" required placeholder="Email" /><br>
			<input id="phone" class="initial-field" maxlength="40" name="phone" size="20" type="text" required placeholder="Phone"/><br>
			<a class="continue" id="submit-fields">Continue</a>
		</div>

		<div id="product-type" class="screen">
			<h1>Do you need a developer for an existing project or a new one?</h1>
			<div class="card">
				<div class="inner">
					<svg viewBox="0 0 71.39 103.57"><path d="M87.48,35.69A35.69,35.69,0,1,0,30.23,64.1h0c.16.11.33.24.51.37l.05,0a18.55,18.55,0,0,1,6.73,14v25H66.06v-25a17.68,17.68,0,0,1,7.14-14.3h0A35.59,35.59,0,0,0,87.48,35.69ZM41.07,100V89.23H62.49V100ZM51.78,39.26a3.57,3.57,0,1,1,3.57-3.57A3.57,3.57,0,0,1,51.78,39.26ZM71,61.36l-.14.11a21.18,21.18,0,0,0-8.42,17v7.14H53.57V42.58a7.14,7.14,0,1,0-3.58,0V85.66H41.07V78.52A22.16,22.16,0,0,0,33,61.75l-.1-.08-.34-.26-.19-.15A32.11,32.11,0,1,1,83.9,35.69,31.84,31.84,0,0,1,71,61.36Z" transform="translate(-16.09)"></path></svg>
					<h2>New idea or product build</h2>
				</div><!--end inner-->
			</div><!--end card-->
			<div class="card">
				<div class="inner">
					<svg viewBox="0 0 132.99 103.57"><path d="M0,79.94v10a3.67,3.67,0,0,0,3.86,3.82H39.41v5.13a4.54,4.54,0,0,0,4.77,4.72h84A4.54,4.54,0,0,0,133,98.85V86.53a7.34,7.34,0,0,0-4.77-7.27c-4.85-2.12-17.84-6.82-26-9.23-.63-.2-.74-.23-.74-3a16.05,16.05,0,0,1,1.08-6.72,33.55,33.55,0,0,0,2.62-9c1.2-1.39,2.83-4.13,3.88-9.36.92-4.61.49-6.28-.12-7.85a3.92,3.92,0,0,1-.18-.52c-.23-1.06.08-6.67.88-11,.54-3-.14-9.33-4.25-14.59C102.8,4.64,97.82.56,88.9,0h-5c-9.08.57-14,4.64-16.64,8C63.17,13.22,62.49,19.57,63,22.56c.79,4.35,1.11,10,.87,11,0,.16-.12.33-.18.49-.61,1.57-1,3.25-.12,7.85,1,5.23,2.68,8,3.88,9.36a33.9,33.9,0,0,0,2.62,9A16.14,16.14,0,0,1,72,66.9c0,2.8-.11,2.84-.78,3-1.68.49-3.58,1.09-5.58,1.73C60.91,69.92,55,67.87,50.68,66.6c-.51-.16-.6-.19-.6-2.45A13,13,0,0,1,51,58.72a27.29,27.29,0,0,0,2.13-7.26c1-1.12,2.29-3.34,3.13-7.56.75-3.72.39-5.08-.1-6.35a3.64,3.64,0,0,1-.15-.42,41.58,41.58,0,0,1,.7-8.91,16.39,16.39,0,0,0-3.43-11.8c-2.1-2.68-6.12-6-13.32-6.44h-4c-7.33.46-11.35,3.76-13.45,6.44A16.34,16.34,0,0,0,19,28.22a41.76,41.76,0,0,1,.71,8.93c0,.13-.1.26-.15.4-.5,1.27-.84,2.62-.1,6.35.85,4.23,2.16,6.44,3.13,7.56a27.52,27.52,0,0,0,2.12,7.26,13.08,13.08,0,0,1,1.52,5.35c0,2.27-.08,2.29-.63,2.46-6.38,1.88-16.68,5.46-21,7.29A7,7,0,0,0,0,79.94Zm44.33,6.59c0-.73.73-2.21,2.7-3,5.18-2.16,17.87-6.54,25.58-8.81,4.31-1.35,4.31-5.06,4.31-7.77a20.55,20.55,0,0,0-2.35-8.71,29.11,29.11,0,0,1-2.2-7.53l-.19-1.46-1-1.11c-.53-.62-1.86-2.54-2.77-7.11-.71-3.57-.4-4.39-.12-5.1h0l0-.12a7.24,7.24,0,0,0,.26-.76l.05-.18,0-.18c.5-2.35-.15-9.13-.85-12.95A15.4,15.4,0,0,1,71.15,11C74,7.36,78.35,5.31,84.07,4.93h4.66c7,.48,10.81,3.56,12.78,6.07a15.41,15.41,0,0,1,3.29,10.67c-.68,3.72-1.35,10.61-.85,12.94l0,.1,0,.1a8.59,8.59,0,0,0,.38,1.11c.25.66.57,1.49-.14,5.05-.92,4.58-2.24,6.49-2.77,7.1l-1,1.12-.19,1.46a28.61,28.61,0,0,1-2.2,7.52A20.55,20.55,0,0,0,96.55,67c0,2.7,0,6.4,4.17,7.73,7.94,2.35,20.85,7,25.51,9a2.51,2.51,0,0,1,1.83,2.76V98.64H44.35l0-12.11ZM4.93,79.94c0-.19.31-1,1.6-1.6,4.22-1.77,14.4-5.29,20.49-7.09,4.16-1.32,4.16-5,4.16-7.19a17.51,17.51,0,0,0-2-7.45,22.88,22.88,0,0,1-1.69-5.81l-.19-1.46-1-1.11c-.3-.34-1.32-1.75-2-5.32-.53-2.62-.31-3.16-.15-3.6l0-.07,0-.07a5.8,5.8,0,0,0,.23-.66l.06-.18,0-.18c.48-2.27-.21-8.28-.68-10.84a11.63,11.63,0,0,1,2.47-7.89c2.13-2.71,5.39-4.24,9.72-4.54h3.7c5.29.38,8.14,2.67,9.61,4.54a11.66,11.66,0,0,1,2.47,7.89c-.5,2.71-1.15,8.64-.69,10.82l0,.1,0,.1a9.43,9.43,0,0,0,.32,1c.17.43.38,1-.14,3.6-.72,3.58-1.74,5-2,5.31l-1,1.11-.19,1.46a22.42,22.42,0,0,1-1.7,5.81,17.49,17.49,0,0,0-1.34,7.53c0,2.2,0,5.87,4,7.15,2.55.76,5.72,1.8,8.86,2.89C52.86,76,47.92,77.77,45.07,79c-3.84,1.65-5.68,4.91-5.68,7.57v2.26H4.92V79.94Z"></path></svg>
					<h2>Existing product that needs more resources</h2>
				</div><!--end inner-->
			</div><!--end card-->
			<div class="card">
				<div class="inner">
					<svg viewBox="0 0 103.57 103.57"><path d="M52,33a6.21,6.21,0,1,0-6.21-6.21A6.21,6.21,0,0,0,52,33Z"></path><path d="M56.26,41.25V37.11H43.84v4.14H48V76.47H43.84v4.14H60.41V76.47H56.26Z"></path><path d="M51.79,0a51.78,51.78,0,1,0,51.78,51.78A51.79,51.79,0,0,0,51.79,0Zm0,99.43A47.64,47.64,0,1,1,99.43,51.78,47.7,47.7,0,0,1,51.79,99.43Z"></path></svg>
					<h2>Neither, just looking to learn more about TalentCrank</h2>
				</div><!--end inner-->
			</div><!--end card-->
			<div class="clearfix"></div>
		</div><!--end reach-->

		<div id="remote-dev" class="screen">
			<h1>Are you open to working with a remote developer if we find a perfect match?</h1>
			<div class="card">
				<div class="inner">
					<svg viewBox="0 0 107.6 103.25"><path d="M107.6,50.2a9,9,0,0,0-9.37-9H64.56c2.15-5,6-15.46,6-26.27C70.57,2.59,64.38,0,59.19,0,56,0,51.32,2.44,51.32,6.85c0,1.86-.3,18.43-10.41,26.91-10.78,9-13.84,11.4-19.74,11.4-6.89,0-19.12.15-19.12.15l-2,0V93.5H22.53c1.46,0,5.85,2,9.73,3.68,6.4,2.85,13.65,6.08,18.53,6.08H83.33A9.46,9.46,0,0,0,92.94,94,9,9,0,0,0,91,88.41a9.19,9.19,0,0,0,4.79-14.53,9.45,9.45,0,0,0,6.62-9,9.36,9.36,0,0,0-1.91-5.65,9.36,9.36,0,0,0,7.1-9.05Zm-9.37,5.22H91v4.14h1.88a5.34,5.34,0,1,1,0,10.67h-6v4.14h1.55a5.15,5.15,0,0,1,0,10.3H82.77v4.14h.56a5.16,5.16,0,1,1,0,10.3H50.79c-4,0-11.12-3.17-16.84-5.72s-9.18-4-11.41-4H4.14V49.43c3.77,0,11.89-.13,17-.13,7.65,0,11.67-3.37,22.4-12.37C54.88,27.44,55.46,10.21,55.46,6.85c0-1.69,2.59-2.71,3.74-2.71,2.17,0,7.25,0,7.25,10.79,0,13.63-6.88,27.26-7,27.4l-1.54,3H83.3a.09.09,0,0,0,.12,0H98.24a4.92,4.92,0,0,1,5.23,4.86,5.23,5.23,0,0,1-5.23,5.22Z"></path></svg>
					<h2>Yes</h2>
				</div><!--end inner-->
			</div><!--end card-->
			<div class="card">
				<div class="inner">
					<svg viewBox="0 0 107.6 103.25"><path d="M0,53.06a9,9,0,0,0,9.37,9H43c-2.15,5-6,15.46-6,26.27,0,12.34,6.19,14.93,11.39,14.93,3.22,0,7.87-2.44,7.87-6.85,0-1.86.3-18.43,10.41-26.91,10.78-9,13.84-11.4,19.74-11.4,6.89,0,19.12-.15,19.12-.15l2,0V9.76H85.07c-1.46,0-5.85-2-9.73-3.68C68.94,3.23,61.69,0,56.81,0H24.27A9.46,9.46,0,0,0,14.66,9.3a9,9,0,0,0,1.95,5.54,9.19,9.19,0,0,0-4.79,14.53,9.45,9.45,0,0,0-6.62,9A9.36,9.36,0,0,0,7.11,44,9.36,9.36,0,0,0,0,53.05Zm9.37-5.22h7.19V43.69H14.67a5.34,5.34,0,0,1,0-10.67h6V28.88H19.14a5.15,5.15,0,0,1,0-10.3h5.69V14.45h-.56A5.33,5.33,0,0,1,18.79,9.3a5.32,5.32,0,0,1,5.47-5.15H56.81c4,0,11.12,3.17,16.84,5.72s9.18,4,11.41,4h18.4V53.82c-3.77,0-11.89.13-17,.13-7.65,0-11.67,3.37-22.4,12.37C52.72,75.81,52.14,93,52.14,96.4c0,1.69-2.59,2.71-3.74,2.71-2.17,0-7.25,0-7.25-10.79,0-13.63,6.88-27.26,7-27.4l1.54-3H24.3a.09.09,0,0,0-.12,0H9.36a4.92,4.92,0,0,1-5.23-4.86,5.23,5.23,0,0,1,5.23-5.22Z" transform="translate(0 0)"></path></svg>
					<h2>No</h2>
				</div><!--end inner-->
			</div><!--end card-->
			<div class="clearfix"></div>
		</div>

		<div id="timeframe" class="screen">
			<h1>How soon do you need the developer?</h1>
			<div class="card">
				<div class="inner">
					<svg viewBox="0 0 107.61 107.61"><path d="M53.8,0a53.55,53.55,0,0,1,53.8,53.8,53.55,53.55,0,0,1-53.8,53.8A53.55,53.55,0,0,1,0,53.8,57.82,57.82,0,0,1,1,42.94a41.64,41.64,0,0,1,3.28-10.1,52.68,52.68,0,0,1,4.93-9.09,65.7,65.7,0,0,1,6.44-8.08l3,3a62.46,62.46,0,0,0-5.94,7.45,54.56,54.56,0,0,0-4.67,8.46A50.88,50.88,0,0,0,5.3,43.83a49.43,49.43,0,0,0-1,10A47.69,47.69,0,0,0,8.21,73.13,53.18,53.18,0,0,0,18.69,88.92,53.19,53.19,0,0,0,34.48,99.4a47.69,47.69,0,0,0,19.32,3.92A47.72,47.72,0,0,0,73.13,99.4,53.24,53.24,0,0,0,88.92,88.92,53.35,53.35,0,0,0,99.4,73.13a47.76,47.76,0,0,0,3.92-19.32,48.42,48.42,0,0,0-3.66-18.69A49.82,49.82,0,0,0,74.52,8.84,48.85,48.85,0,0,0,56.08,4.29V25.77H51.53V2.27h.25V0h2ZM48.5,57.59l-20.21-26a2.25,2.25,0,0,1-.38-1.64,1.94,1.94,0,0,1,.88-1.39,1.83,1.83,0,0,1,2.53,0l26.27,20c.17,0,.34.17.51.51a9.88,9.88,0,0,1,1.64,2.15,5.41,5.41,0,0,1,.63,2.65,6.77,6.77,0,0,1-.51,2.53,4.62,4.62,0,0,1-1.52,2,4.58,4.58,0,0,1-2,1.52,6.72,6.72,0,0,1-2.53.51A5.3,5.3,0,0,1,51,59.61a11.51,11.51,0,0,1-2.27-1.77Z"></path></svg>
					<h2>Immediately</h2>
				</div><!--end inner-->
			</div><!--end card-->
			<div class="card">
				<div class="inner">
					<svg viewBox="0 0 75.53 107.61"><path d="M64.41,29.81a22.43,22.43,0,0,1-.76,5.68,17.28,17.28,0,0,1-3,5.94A30.11,30.11,0,0,1,54.06,48a61.59,61.59,0,0,1-11.37,6.57A83.17,83.17,0,0,1,52.92,60.5a33.89,33.89,0,0,1,6.69,5.94,22.55,22.55,0,0,1,3.66,6.82,25.51,25.51,0,0,1,1.14,7.83v22.23H75.53v4.29H0v-4.29H10.86V81.09A25.51,25.51,0,0,1,12,73.25a22.46,22.46,0,0,1,3.66-6.82,31.06,31.06,0,0,1,6.69-5.81,96.47,96.47,0,0,1,10.23-5.81,56.81,56.81,0,0,1-11.37-6.69,36.7,36.7,0,0,1-6.57-6.44,15.91,15.91,0,0,1-3.16-6.19,26.58,26.58,0,0,1-.63-5.68V4.29H0V0H75.53V4.29H64.41Zm-27,22.73a86.17,86.17,0,0,0,12-6.57,40,40,0,0,0,6.95-5.81,18.37,18.37,0,0,0,3-5.56,16.7,16.7,0,0,0,.76-4.8V4.29h-45V29.81a19.71,19.71,0,0,0,.63,4.8,14.55,14.55,0,0,0,3.16,5.56A32,32,0,0,0,25.64,46,91.26,91.26,0,0,0,37.39,52.54ZM60.12,81.09a22.1,22.1,0,0,0-.88-6.44A16.8,16.8,0,0,0,56.08,69a36.65,36.65,0,0,0-6.95-5.68,92.34,92.34,0,0,0-11.49-6.19,105.94,105.94,0,0,0-11.49,6.06A29.11,29.11,0,0,0,19.2,69,16.73,16.73,0,0,0,16,74.64a22,22,0,0,0-.88,6.44v22.23h45Z"></path></svg>
					<h2>In 1-2 weeks</h2>
				</div><!--end inner-->
			</div><!--end card-->
			<div class="card">
				<div class="inner">
					<svg viewBox="0 0 103.57 103.57"><path d="M0,0H103.57V103.57H0ZM99.27,4.29h-95V23.74h95Zm-95,95h95V28h-95Zm28-88.41v6.57H38.9V10.86Zm32.33,0v6.57h6.57V10.86Z"></path><path d="M78.81,41A2.53,2.53,0,0,1,81,40a3.3,3.3,0,0,1,2.4,1,3.9,3.9,0,0,1,.76,2.4,2.53,2.53,0,0,1-1,2.15L46.23,81.41a1.18,1.18,0,0,1-.88.63,5.65,5.65,0,0,1-1.14.13A6.86,6.86,0,0,1,42.82,82a1.17,1.17,0,0,1-.88-.63L27.53,67a3.27,3.27,0,0,1-1-2.4,2.53,2.53,0,0,1,1-2.15,2.53,2.53,0,0,1,2.15-1,3.3,3.3,0,0,1,2.4,1L44.21,74.59Z"></path></svg>
					<h2>2+ weeks</h2>
				</div><!--end inner-->
			</div><!--end card-->
			<div class="clearfix"></div>

			<select style="visibility:hidden;" id="00N3100000H9qBW" name="00N3100000H9qBW" title="Product Type"><option value="New idea or product build">New idea or product build</option>
				<option value="Existing product that needs more resources">Existing product that needs more resources</option>
				<option value="Neither, just looking to learn more about TalentCrank">Neither, just looking to learn more about TalentCrank</option>
			</select><br>

			<select style="visibility:hidden;" id="00N3100000H9qBb" name="00N3100000H9qBb" title="Remote Developer?">
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select><br>

			<select style="visibility:hidden;" id="00N3100000H9o8u" name="00N3100000H9o8u" title="Developer Timeframe">
				<option value="Immediately">Immediately</option>
				<option value="1-2 Weeks">1-2 Weeks</option>
				<option value="Over 2 Weeks">Over 2 Weeks</option>
			</select><br>

			<input type="submit" name="submit" value="Send" />

		</div><!--end timeframe-->

		<!-- <div id="thank-you" class="screen">
			<h1>We are on it! A TalentCrank specialist will be reaching out shortly.</h1>
			<p>Head back to our <a href="/">homepage</a>.</p>
		</div> -->

	</div><!--end main-wrap-->

	</form><!--end form-->

</div><!--end form-body-->

<script>

	(function($){

		/*
	    |--------------------------------------------------------------------------
	    | Screens
	    |--------------------------------------------------------------------------
	    | This Object houses the functionality for moving through the steps fo the form
		| The 'initial fields' in step 1 are also validated in this object
	    |
	    */
		Screens = {
			screen: '.screen',
			timecard: '#timeframe .inner',
			productCard: '#product-type .inner',
			remoteCard: '#remote-dev .inner',
			submitFields: '#submit-fields',
			progressBar: '.progress-bar',
			init: function() {
				var self = this;
				$(this.submitFields).click(this.validateFields.bind(this));
				$(this.productCard).click(function(){
					self.showScreen();
					self.changeProgress("three");
				})
				$(this.remoteCard).click(function(){
					self.showScreen();
					self.changeProgress("four");
				})
				$(this.productCard).click(this.showScreen.bind(this));
				$(this.remoteCard).click(this.showScreen.bind(this));
			},
			showScreen: function() {
				var activeScreen = $('.screen.active'),
					nextScreen = activeScreen.next('.screen');
				var is_chrome = navigator.userAgent.indexOf('Chrome') > -1,
			    	is_explorer = navigator.userAgent.indexOf('MSIE') > -1,
			    	is_firefox = navigator.userAgent.indexOf('Firefox') > -1,
			    	is_safari = navigator.userAgent.indexOf("Safari") > -1,
			    	is_opera = navigator.userAgent.toLowerCase().indexOf("op") > -1;
			    if ((is_chrome)&&(is_safari)) {is_safari=false;}
			    if ((is_chrome)&&(is_opera)) {is_chrome=false;}
				activeScreen.css({
					"-webkit-transform": "translateX(-1000px)",
					"-ms-transform": "translateX(-1000px)",
					"transform": "translateX(-1000px)"
				});
				activeScreen.fadeOut("slow");
				if ( is_safari == true ) {
					activeScreen.removeClass("active");
					nextScreen.addClass("active");
				} else {
					setTimeout(function(){
						activeScreen.removeClass("active");
						nextScreen.addClass("active");
					}, 400);
				}
			},
			changeProgress: function(value) {
				$(this.progressBar).addClass(value);
			},
			validateFields: function() {
				var is_chrome = navigator.userAgent.indexOf('Chrome') > -1,
			    	is_explorer = navigator.userAgent.indexOf('MSIE') > -1,
			    	is_firefox = navigator.userAgent.indexOf('Firefox') > -1,
			    	is_safari = navigator.userAgent.indexOf("Safari") > -1,
			    	is_opera = navigator.userAgent.toLowerCase().indexOf("op") > -1;
			    if ((is_chrome)&&(is_safari)) {is_safari=false;}
			    if ((is_chrome)&&(is_opera)) {is_chrome=false;}

			    // HTML5 Validation is rejected in Safari so we have to work around
			    if ( is_safari == true) {
			    	var fields = $("#dev-form input.initial-field");
			  		var first = document.forms["dev-form"]["first_name"].value,
			  			last = document.forms["dev-form"]["last_name"].value,
			  			company = document.forms["dev-form"]["company"].value,
			  			email = document.forms["dev-form"]["email"].value,
			  			phone = document.forms["dev-form"]["phone"].value,
			  			fieldsPass = false;

			  		// Safari Validation
				    if (first == "") {
				        alert("First Name must be filled out");
				        return false;
				    } else if (last == "") {
				    	 alert("Last Name must be filled out");
				        return false;
				    } else if (company == "") {
				    	 alert("Company must be filled out");
				        return false;
				    } else if (email == "") {
				    	 alert("Email must be filled out");
				        return false;
				    } else if (phone == "") {
				    	 alert("Phone Number must be filled out");
				        return false;
				    } else {
				    	// run the showscreen function
						Screens.showScreen();
						// update the progress bar
						$(this.progressBar).addClass("two");
						// push up the new state and add hash to url
						window.history.pushState("", "page", "#step-2");
				    }
			   	// For all other browsers...
			    } else {
			    	// set the fields we want to validate
			    	var fields = $("#dev-form input.initial-field"),
			    		// set default to false
						fieldsPass = false;
					// run the following function for each field in the initial set
					fields.each(function(x){
						// if the field does not validate
						if (!fields[x].checkValidity()) {
							// run the overally form submit so we can see the error
							$("#dev-form").find(':submit').click();
						}
						// run a loop set to the length of the field so we can when all fields validate
						for ( var x = 0; x < fields.length; x++ ) {
							// if the field does not validate
							if ( !fields[x].checkValidity() ) {
								// set to false
								fieldsPass = false;
								break;
							// if all fields validate
							} else {
								// set to true
								fieldsPass = true;
							}
						}
					});
					// If all of our fields have validated
					if (fieldsPass === true) {
						// run the showscreen function
						Screens.showScreen();
						// update the progress bar
						$(this.progressBar).addClass("two");
						// push up the new state and add hash to url
						window.history.pushState("", "page", "#step-2");
					}
			    }
			}
		}

		
		/*
	    |--------------------------------------------------------------------------
	    | MultiSelect Functionality
	    |--------------------------------------------------------------------------
	    | This MultiSelect Object is a combination of the three objects below
		| This is much cleaner but not working in IE and I can't figure out why
	    |
	    */
		// MultiSelect = {
		// 	init: function() {
		// 		var divs = ['product-type', 'remote-dev', 'timeframe'],
		// 			ids = ['00N3100000H9qBW', '00N3100000H9qBb', '00N3100000H9o8u']
		// 			divCount = divs.length;
		// 		for ( let i=0; i < divCount; i++ ) {
		// 			var card = $('#' + divs[i] + ' div.inner');
		// 			card.click(function(e){
		// 				var target = $(e.target),
		// 					selectedIndex = target.index('#' + divs[i] + ' div.inner'),
		// 					selectedOption = $('select#' + ids[i] +  ' option').eq(selectedIndex);
		// 				selectedOption.prop('selected', true);
		// 				card.removeClass("active");
		// 				target.addClass("active");
		// 				if ( i == (divCount - 1) ) {
		// 					$("#dev-form input[type=submit]").fadeIn();
		// 					if ( $(window).width() < 768 ) {
		// 						$("html, body").animate({ scrollTop: submitTop });
		// 					}
		// 				}
		// 			})
		// 		}
		// 	}
		// }

		ProductType = {
			productCard: '#product-type div.inner',
			init: function() {
				$(this.productCard).click(this.selectOption.bind(this));
			},
			selectOption: function(e) {
				e.preventDefault();
				var target = $(e.target),
					selectedIndex = target.index(this.productCard),
					selectedOption = $('select#00N3100000H9qBW option').eq(selectedIndex);
				selectedOption.prop('selected', true);
				$(this.productCard).removeClass("active");
				target.addClass("active");
				window.history.pushState("", "page", "#step-3");
			}
		}

		RemoteDev = {
			remoteCard: '#remote-dev div.inner',
			init: function() {
				$(this.remoteCard).click(this.selectOption.bind(this));
			},
			selectOption: function(e) {
				e.preventDefault();
				var target = $(e.target),
					selectedIndex = target.index(this.remoteCard),
					selectedOption = $('select#00N3100000H9qBb option').eq(selectedIndex);
				selectedOption.prop('selected', true);
				$(this.remoteCard).removeClass("active");
				target.addClass("active");
				window.history.pushState("", "page", "#step-4");
			}
		}

		Timeframe = {
			timecard: '#timeframe div.inner',
			init: function() {
				$(this.timecard).click(this.selectOption.bind(this));
			},
			selectOption: function(e) {
				e.preventDefault();
				var target = $(e.target),
					selectedIndex = target.index(this.timecard),
					selectedOption = $('select#00N3100000H9o8u option').eq(selectedIndex),
					submitTop = $("#dev-form").find(':submit').offset().top;
				selectedOption.prop('selected', true);
				$(this.timecard).removeClass("active");
				target.addClass("active");
				$("#dev-form").find(':submit').fadeIn();
				if ( $(window).width() < 768 ) {
					$("html, body").animate({ scrollTop: submitTop });
				}
			}
		}
		
		$(document).ready(function() {
        	Screens.init();
        	ProductType.init();
        	RemoteDev.init();
        	Timeframe.init();
        	// MultiSelect.init();

        	// Stop enter from submitting form since its multiple steps
        	$(window).keydown(function(e){
				if(e.keyCode == 13) {
					e.preventDefault();
					return false;
				}
			});

			// Manipulate screens on history change based on hash routing
			$(window).on("popstate", function(){
				var hash = window.location.hash,
					stepNumber = hash.substr(hash.length - 1);
				$('.screen').removeClass("active");
				if ( stepNumber ) {
					$('.screen').eq(stepNumber - 1).addClass("active").attr("style", "");
				} else {
					$('.screen').eq(0).addClass("active").attr("style", "");
				}
			});

			if (performance.navigation.type  == 1 ) {
				window.location.hash = '';
			}
			
    	});

	})(jQuery);

</script>