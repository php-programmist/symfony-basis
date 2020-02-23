import '../../scss/libs/lazy_youtube.scss'

( function() {
	const youtube = document.querySelectorAll(".youtube");
	for (let i = 0; i < youtube.length; i++) {
		const source = "https://i.ytimg.com/vi/" + youtube[i].dataset.embed + "/hqdefault.jpg";
		const image = new Image();
		image.src = source;
		image.addEventListener( "load", function() {
			youtube[ i ].appendChild( image );
		}( i ) );
		
		youtube[i].addEventListener( "click", function() {
			const iframe = document.createElement("iframe");
			iframe.setAttribute( "frameborder", "0" );
			iframe.setAttribute( "allowfullscreen", "" );
			iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ this.dataset.embed +"?rel=0&showinfo=0&autoplay=1" );
			this.innerHTML = "";
			this.appendChild( iframe );
		} );
	}
} )();