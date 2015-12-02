<!-- Start Body Footer -->

</div>
<div id="footer">
	<strong>Meetup</strong> created by <a href="http://github.com/Andrew-Zarenberg" target="_blank">Andrew Zarenberg</a>
</div>

<script type="text/javascript">

// Add confirm message to all "bad" links
// (ex: are you sure you wish to leave this group?)
var a = document.getElementsByTagName("a");
for(var x=0;x<a.length;x++){
	if(a[x].className == "bad"){// && a[x].parentNode.className == "actions"){
		a[x].onclick = function(e){
			e.preventDefault ? e.preventDefault() : e.returnValue = false;
			
			if(confirm("Are you sure you wish to "+e.srcElement.innerHTML+"?")){
				location.href = e.srcElement.href;
			}
		};
	}
}
</script>
<!-- End Body Footer -->