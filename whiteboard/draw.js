	/* © 2009 ROBO Design
	* http://www.robodesign.ro
	*/

	// Keep everything in anonymous function, called on window load.

if(window.addEventListener) {
	
	loadPaint=function () {
		var canvas, context, canvaso, contexto;

		// The active tool instance.
		var tool;
		var tool_default = 'line';

		function init () {
			// Find the canvas element.
			canvaso = document.getElementById('board');
			
			if (!canvaso) {
				alert('Error: I cannot find the canvas element!');
				return;
			}

			if (!canvaso.getContext) {
				alert('Error: no canvas.getContext!');
				return;
			}
			canvaso.width=document.body.clientWidth;
			canvaso.height=document.body.clientHeight;

			// Get the 2D canvas context.
			contexto = canvaso.getContext('2d');
			if (!contexto) {
				alert('Error: failed to getContext!');
				return;
			}
			// Add the temporary canvas.
			var container = canvaso.parentNode;
			canvas = document.createElement('canvas');
			if (!canvas) {
				alert('Error: I cannot create a new canvas element!');
				return;
			}

			canvas.id     = 'imageTemp';
			canvas.width=canvaso.width;
			canvas.height=canvaso.height;
			canvas.clientWidth=canvaso.clientWidth;
			canvas.clientHeight=canvaso.clientHeight;
			context = canvas.getContext('2d');
			container.appendChild(canvas);

			// Get the tool select input.
			var tool_select = document.getElementById('dtool');
			if (!tool_select) {
				alert('Error: failed to get the dtool element!');
				return;
			}
			
			tool_select.addEventListener('change', ev_tool_change, false);

			// Activate the default tool.
			if (tools[tool_default]) {
				tool = new tools[tool_default]();
				tool_select.value = tool_default;
			}

			// Attach the event listeners.
			canvas.addEventListener('mousedown', ev_canvas, false);
			canvas.addEventListener('mousemove', ev_canvas, false);
			canvas.addEventListener('mouseup',   ev_canvas, false);
			canvas.addEventListener('mouseout',   ev_canvas, false);
			document.body.addEventListener('keydown',   ev_canvas, false);
			document.body.addEventListener('keyup',   ev_canvas, false);
		}

		// The general-purpose event handler. This function just determines the mouse 
		// position relative to the canvas element.
		function ev_canvas (ev) {
			if (ev.layerX || ev.layerX == 0) { // Firefox
				ev._x = ev.layerX;
				ev._y = ev.layerY;
			} 
			else if (ev.offsetX || ev.offsetX == 0) { // Opera
				ev._x = ev.offsetX;
				ev._y = ev.offsetY;
			}
		// Call the event handler of the tool.
			var func = tool[ev.type];
			if (func) {
				func(ev);
			}
		}

		// The event handler for any changes made to the tool selector.
		function ev_tool_change (ev) {
			if (tools[this.value]) {
				tool = new tools[this.value]();
			}
		}

		// This function draws the #imageTemp canvas on top of #board, after which 
		// #imageTemp is cleared. This function is called each time when the user 
		// completes a drawing operation.
		function img_update () {
			contexto.drawImage(canvas, 0, 0);
			context.clearRect(0, 0, canvas.width, canvas.height);
		}

		// This object holds the implementation of each drawing tool.
		var tools = {};

		// The drawing pencil.
		tools.pencil = function () {
			var tool = this;
			this.started = false;

			// This is called when you start holding down the mouse button.
			// This starts the pencil drawing.
			this.mousedown = function (ev) {
				context.beginPath();
				context.moveTo(ev._x, ev._y);
				tool.started = true;
			};

			// This function is called every time you move the mouse. Obviously, it only 
			// draws if the tool.started state is set to true (when you are holding down 
			// the mouse button).
			this.mousemove = function (ev) {
			  if (tool.started) {
				context.lineTo(ev._x, ev._y);
				context.stroke();
			  }
			};

			// This is called when you release the mouse button.
			this.mouseup = function (ev) {
			  if (tool.started) {
				tool.mousemove(ev);
				tool.started = false;
				img_update();
			  }
			};
		};
		
		// Draws arrows
		tools.arrow = function () {
			var tool = this;
			this.started = false;
			this.lastpointsx=new Array();
			this.lastpointsy=new Array();
			this.lastpointsx[0]=0;
			this.lastpointsy[0]=0;
			this.counter=0;
			// This is called when you start holding down the mouse button.
			// This starts the pencil drawing.
			this.mousedown = function (ev) {
				context.beginPath();
				context.moveTo(ev._x, ev._y);
				tool.started = true;
				tool.lastpointsx[0]=ev._x;
				tool.lastpointsy[0]=ev._y;
				tool.counter=0;
			};

			// This function is called every time you move the mouse. Obviously, it only 
			// draws if the tool.started state is set to true (when you are holding down 
			// the mouse button).
			this.mousemove = function (ev) {
				if (tool.started) {
				context.lineTo(ev._x, ev._y);
				context.stroke();
				}
				tool.counter+=1;
				if (tool.counter==20){ tool.counter=0;}
				tool.lastpointsx[tool.counter]=ev._x;
				tool.lastpointsy[tool.counter]=ev._y;
			};

			// This is called when you release the mouse button.
			this.mouseup = function (ev) {
				if (tool.started) {
					tool.mousemove(ev);
					tool.started = false;
					tool.counter+=2;
					if (tool.counter==20) tool.counter=0;

					angle=Math.atan2(tool.lastpointsy[tool.counter]-ev._y,tool.lastpointsx[tool.counter]-ev._x);
					var arrowlength=15.0;
					var arrowangle=Math.PI/6;

					// Half the arrow
					context.beginPath();
					context.moveTo(ev._x,ev._y);
					context.lineTo(ev._x+arrowlength*Math.cos(angle+arrowangle),ev._y+arrowlength*Math.sin(angle+arrowangle));
					context.stroke(); 

					// The other half
					context.beginPath();
					context.moveTo(ev._x,ev._y);
					context.lineTo(ev._x+arrowlength*Math.cos(angle-arrowangle),ev._y+arrowlength*Math.sin(angle-arrowangle));
					context.closePath();
					context.stroke(); 
					img_update();
				}
			};
		};
		// The rectangle tool.
		tools.rect = function () {
			var tool = this;
			this.started = false;

			this.mousedown = function (ev) {
				tool.started = true;
				tool.x0 = ev._x;
				tool.y0 = ev._y;
			};

			this.mousemove = function (ev) {
			  if (!tool.started) {
				return;
			  }

			  var x = Math.min(ev._x,  tool.x0),
				y = Math.min(ev._y,  tool.y0),
				w = Math.abs(ev._x - tool.x0),
				h = Math.abs(ev._y - tool.y0);

				context.clearRect(0, 0, canvas.width, canvas.height);

			  if (!w || !h) {
				return;
			  }

			  context.strokeRect(x, y, w, h);
			};

			this.mouseup = function (ev) {
				if (tool.started) {
					tool.mousemove(ev);
					tool.started = false;
					img_update();
				}
			};
		};
		
		// The eraser tool
		tools.eraser = function () {
			var tool = this;
			this.started = false;

			this.mousedown = function (ev) {
				tool.started = true;
				tool.x0 = ev._x;
				tool.y0 = ev._y;
			};

			this.mousemove = function (ev) {
				var erasersize=10;
				context.clearRect(0,0,canvas.width,canvas.height);
				context.fillStyle="#FFFFFF";
				context.fillRect(ev._x-erasersize/2,ev._y-erasersize/2,erasersize,erasersize);
				if (tool.started)
					img_update();
				context.strokeRect(ev._x-erasersize/2,ev._y-erasersize/2,erasersize,erasersize);
			};
			this.mouseout = function (ev) {
				context.clearRect(0,0,canvas.width,canvas.height);
			};
			this.mouseup = function (ev) {
				if (tool.started) {
					tool.mousemove(ev);
					tool.started = false;
				}
			};
		};

		// The circle tool
		tools.circle = function () {
			var tool = this;
			this.started = false;

			this.mousedown = function (ev) {
				tool.started = true;
				tool.x0 = ev._x;
				tool.y0 = ev._y;
			};

			this.mousemove = function (ev) {
				if (!tool.started) {
					return;
				}
				context.clearRect(0, 0, canvas.width, canvas.height);
				context.beginPath();
				var r=Math.sqrt((ev._x-tool.x0)*(ev._x-tool.x0)+(ev._y-tool.y0)*(ev._y-tool.y0));
				context.arc(tool.x0, tool.y0, r, 0, 2*Math.PI);
				context.stroke();
				context.closePath();
			};

			this.mouseup = function (ev) {
				if (tool.started) {
					tool.mousemove(ev);
					tool.started = false;
					img_update();
				}
			};
		};


		// The line tool.
		tools.line = function () {
			var tool = this;
			this.started = false;

			this.mousedown = function (ev) {
				tool.started = true;
				tool.x0 = ev._x;
				tool.y0 = ev._y;
			};

			this.mousemove = function (ev) {
				if (!tool.started) return;

				context.clearRect(0, 0, canvas.width, canvas.height);

				context.beginPath();
				context.moveTo(tool.x0, tool.y0);
				context.lineTo(ev._x,   ev._y);
				context.stroke();
				context.closePath();
			};

			this.mouseup = function (ev) {
				if (tool.started) {
					tool.mousemove(ev);
					tool.started = false;
					img_update();
				}
			};
		};

		// The text writing tool. Doesn't fully work yet.
		tools.text = function (){
			var tool = this;
			this.started=false;
			this.str="";
			this.held=0;
			this.mousedown = function (ev) {
				if (tool.started==true){
					img_update();
					tool.str="";
					tool.x0 = ev._x;
					tool.y0 = ev._y;
				}
				else
				{
					tool.started=true;
					tool.x0 = ev._x;
					tool.y0 = ev._y;
				}
			};
			this.keyup = function (ev){
				if (ev.keyCode==16)
					tool.held=0;
			}
			this.keydown = function (ev) {
				if (tool.started){
					context.clearRect(0, 0, canvas.width, canvas.height);
					switch (ev.keyCode)
					{
						case 16:
							tool.held=16;
						break;
						case 8:
							tool.str=tool.str.substring(0,tool.str.length-1);
						break;
						default:
							if (tool.held==16){//Shift
								tool.str+=String.fromCharCode(ev.keyCode);
							}
							else {
								tool.str+=String.fromCharCode(ev.keyCode+32);
							}
					}
					context.fillText(tool.str,tool.x0,tool.y0);
				}
			};
		}

		init();

	};
	
	window.addEventListener('load', loadPaint, false); 
}

	// vim:set spell spl=en fo=wan1croql tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

