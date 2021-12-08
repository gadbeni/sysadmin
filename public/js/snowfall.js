var t = setInterval(
	function(){
		var documentHeight 		= $(document).height();
		var startPositionLeft 	= Math.random() * $(document).width() - 100;
		var startOpacity		= 0.5 + Math.random();
		var sizeFlake			= 10 + Math.random() * 20;
		var endPositionTop		= documentHeight - 40;
		var endPositionLeft		= startPositionLeft - 100 + Math.random() * 200;
		var durationFall		= documentHeight * 10 + Math.random() * 5000;
		$('#flake')
			.clone()
			.appendTo('body')
			.css(
				{
					left: startPositionLeft,
					opacity: startOpacity,
					'font-size': sizeFlake
				}
			)
			.animate(
				{
					top: endPositionTop,
					left: endPositionLeft,
					opacity: 0.2
				},
				durationFall,
				'linear',
				function() {
					$(this).remove()
				}
			);
	}, 500
);