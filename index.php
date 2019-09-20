
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>Create your own XKCD-style Graphs</title>
    <meta name="description" content="Instant XKCD-style Graphs created in Javascript D3 for your enjoyment" />
    <meta name="author" content="Kevin Xu, Charlie Guo" />
    <!-- <link rel="icon" type="image/x-icon" href="graph.png" /> -->

    <!-- Apple Meta Data -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon.png" />

    <!-- Facebook Meta Data -->
    <meta property="og:url" content="http://xkcdgraphs.com" />
    <meta property="og:title" content="Create your own XKCD-style Graphs" />
    <meta property="og:description" content="Instant XKCD-style Graphs created in Javascript D3 for your enjoyment" />
    <meta property="og:image" content="http://xkcdgraphs.com/graph.png" />
    <meta property="og:type" content="website" />

    <!-- CSS Files -->
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="style.css" />

    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-36917714-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=407882832614060";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

</head>
<body>

    <div class="container">

        <form>
            <div class="left">
                <div class="input">
                    <label for="equation">Equation 1</label>
                    <input type="text" id="equation" placeholder="100x"
                    value="100-x)" />
                </div>

                <div class="input">
                    <label for="equation">Equation 2</label>
                    <input type="text" id="equation2" placeholder="2" value="100*0.95^x" />
                </div>


                <div class="input">
                    <label for="xmin">X-minimum</label>
                    <input type="text" id="xmin" placeholder="0" value="0" />
                </div>

            </div>

            <div class="right">
                <div class="input">
                    <label for="xlabel">X-label</label>
                    <input type="text" id="xlabel" placeholder="Years" value="Years" />
                </div>

                <div class="input">
                    <label for="ylabel">Y-label</label>
                    <input type="text" id="ylabel" placeholder="Value" value="Value" />
                </div>
            </div>

            <div class="clear"></div>
            <label for="xmax" class="slider">X-maximum</label>
            <div id="xslider"></div>

            <div class="clear"></div>
            <label for="slider" class="slider">Refinement</label>
            <div id="slider"></div>

<!--            <span id="more">+</span> -->


            <div class="left">

                <div class="input">
                    <label for="equation4" class="equation4">Equation 4</label>
                    <input type="text" id="equation4" class="equation4" placeholder="1/tan(x)" />
                </div>
            </div>

            <div class="right">
                <div class="input">
                    <label for="equation3" class="equation3">Equation 3</label>
                    <input type="text" id="equation3" class="equation3" placeholder="sqrt(x*10)" />
                </div>

                <div class="input">
                    <label for="equation5" class="equation5">Equation 5</label>
                    <input type="text" id="equation5" class="equation5" placeholder="abs(x)" />
                </div>
            </div>

            <div class="clear"></div>
        </form>
    </div>

    <div id="plot"></div>

    <footer class="container">

    </footer>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script src="d3.v2.min.js"></script>
    <script src="jquery.textchange.min.js"></script>
    <script src="xkcd.js"></script>
    <script src="parser.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $('#equation').focus();
            $("#slider").slider({ min: 1, max: 250, value: 170 });
            $("#xslider").slider({ min: 1, max: 201, value: 70 });
	    drawGraph();
            $("#slider").on("slide", function() {
                drawGraph();
            });
            $("#xslider").on("slide", function() {
                drawGraph();
            });
            $('input').on('textchange', function () {
                drawGraph();
            });
            $('#more').on('click', function() {
                if ($(".equation4").css("display") === "inline-block") $(".equation5").fadeIn();
                if ($(".equation3").css("display") === "inline-block") $(".equation4").fadeIn();
                if ($(".equation2").css("display") === "inline-block") $(".equation3").fadeIn();
                $(".equation2").fadeIn();
            });

            function drawGraph() {
                var plot = xkcdplot();
 //!               drawGraphEquation(plot, $('#equation').val());
//	        drawGraphEquation(plot, $('#equation2').val(), '#DC143C');
	        drawGraphEquation(plot, $('#equation2').val(), '#14DC3C');
//!		drawGraphEquation(plot, $('#equation2').val() + '^x', '#DC143C');
		// var c=document.getElementById("xplot");
		// var d=c.toDataURL("image/png");
		// var w=window.open('about:blank','image from canvas');
		// w.document.write("<img src='"+d+"' alt='from canvas'/>");
            }

            function drawGraphEquation(plot, equation, color) {
                $("#plot").empty();
                if (!color) color = "#000099";

                var expression = string_eval(equation),
                    xmin = parseInt($('#xmin').val()),
                    // xmax = parseInt($('#xmax').val()),
                    N = $("#slider").slider( "option", "value");
                    xmax = $("#xslider").slider( "option", "value");

                if (expression != "'Invalid function'" && !isNaN(xmin) && !isNaN(xmax) && xmin < xmax) {

                    function f(d) {
                        current_expression = expression.split("-x").join(-d);
                        var result = eval(current_expression.split("x").join(d));
                        if (isNaN(result)) {
                            return 0;
                        } else if (result === -Infinity) {
                            return -25;
                        } else if (result === Infinity) {
                            return 25;
                        } else {
                            return result;
                        }
                    }

                    var data = d3.range(xmin, xmax, (xmax - xmin) / N).map(function (d) {
                            return {x: d, y: f(d)};
                        });

                    var parameters = {  title: "",
                                        // xlabel: $('#xlabel').val(),
				        xlabel: $('#xlabel').val() + " = " + xmax,
                                        ylabel: $('#ylabel').val() + " = " + f(xmax), 
// (Math.round(f(xmax) * 1000000000) / 1000000000).toLocaleString('fullwide', {useGrouping:false}),
  					// xlim: [xmin - (xmax - xmin) / 16, xmax + (xmax - xmin) / 16] };
					//					xlim: [-0.2, xmax + (xmax - xmin) / 16] };
					xlim: [0, xmax],
					ylim: [0, f(xmax)] };

					/*
                    if (expression.indexOf("x") < 0) {
                        if (eval(expression) < -10) {
                            parameters["ylim"] = [eval(expression), 10];
                        } else if (eval(expression) > 10) {
                            parameters["ylim"] = [-10, eval(expression)];
                        } else {
                            parameters["ylim"] = [-10, 10];
                        }
                    }*/

                    plot("#plot", parameters);
                    plot.plot(data, {stroke: color});
                    plot.draw();

                    for (var i = xmin; i <= xmax; i++) {
                        current_expression = expression.split("-x").join(-i);
                        var result = eval(current_expression.split("x").join(i));
                        if (isNaN(result) || result === Infinity) {
                            $("#plot").append("<h1>Some part of the equation is invalid along the domain you chose</h1>");
                            break;
                        }
                    }

                    console.log("[Graph Equation] " + $('#equation').val());
                    console.log("[JS Expression] " + expression);
                } else {
                    $("#plot").append("<h1>Sorry, invalid function</h1>");
                    console.log("[Invalid Function] " + $('#equation').val());
                }
            }

        });


    </script>

</body>
</html>
