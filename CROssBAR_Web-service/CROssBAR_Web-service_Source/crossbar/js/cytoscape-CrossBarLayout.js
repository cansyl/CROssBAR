(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["cytoscapeCrossBarLayout"] = factory();
	else
		root["cytoscapeCrossBarLayout"] = factory();
})(this, function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * TODO
 * Choose the type of layout that best suits your usecase as a starting point.
 *
 * A discrete layout is one that algorithmically sets resultant positions.  It
 * does not have intermediate positions.
 *
 * A continuous layout is one that updates positions continuously, like a force-
 * directed / physics simulation layout.
 */
module.exports = __webpack_require__(3);
// module.exports = require('./continuous');

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// Simple, internal Object.assign() polyfill for options objects etc.

module.exports = Object.assign != null ? Object.assign.bind(Object) : function (tgt) {
  for (var _len = arguments.length, srcs = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
    srcs[_key - 1] = arguments[_key];
  }

  srcs.forEach(function (src) {
    Object.keys(src).forEach(function (k) {
      return tgt[k] = src[k];
    });
  });

  return tgt;
};

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var impl = __webpack_require__(0);

// registers the extension on a cytoscape lib ref
var register = function register(cytoscape) {
  if (!cytoscape) {
    return;
  } // can't register if cytoscape unspecified

  cytoscape('layout', 'CrossBarLayout', impl); // register with cytoscape.js
};

if (typeof cytoscape !== 'undefined') {
  // expose to global cytoscape (i.e. window.cytoscape)
  register(cytoscape);
}

module.exports = register;

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

// n.b. .layoutPositions() handles all these options for you
//import * as math from './math';
var assign = __webpack_require__(1);

var defaults = Object.freeze({
  // animation
  animate: undefined, // whether or not to animate the layout
  animationDuration: undefined, // duration of animation in ms, if enabled
  animationEasing: undefined, // easing of animation, if enabled
  animateFilter: function animateFilter(node, i) {
    return true;
  }, // whether to animate specific nodes when animation is on; non-animated nodes immediately go to their final positions

  // viewport
  pan: undefined, // pan the graph to the provided position, given as { x, y }
  zoom: undefined, // zoom level as a positive number to set after animation
  fit: undefined, // fit the viewport to the repositioned nodes, overrides pan and zoom

  // modifications
  padding: undefined, // padding around layout
  boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
  spacingFactor: undefined, // a positive value which adjusts spacing between nodes (>1 means greater than usual spacing)
  nodeDimensionsIncludeLabels: undefined, // whether labels should be included in determining the space used by a node (default true)
  transform: function transform(node, pos) {
    return pos;
  }, // a function that applies a transform to the final node position

  // my modifications
  //orderOfNodeTypes: [6,5,4,3,2,1],
  orderOfNodeTypes: undefined,
  separated: 0,
  lesslayer: 0,

  // layout event callbacks
  ready: function ready() {}, // on layoutready
  stop: function stop() {} // on layoutstop
});

var Layout = function () {
  function Layout(options) {
    _classCallCheck(this, Layout);

    this.options = assign({}, defaults, options);
  }

  _createClass(Layout, [{
    key: 'run',
    value: function run() {
		var layout = this;
		var options = this.options;
		var orderOfNodeTypes = options.orderOfNodeTypes;
		var separated = options.separated;
		var cy = options.cy;
		var r = 100;
		var lesslayer = options.lesslayer;
		var eles = options.eles;
		var nodes = eles.nodes();
		var edges = eles.edges();

		// setting bounding box.
		let bb = { x1: 0, y1: 0, w: cy.width(), h: cy.height() };

		// counting all type of nodes
		var lookup = {Protein_N:0,Protein:0,Pathway:0,kegg_Pathway:0,HPO:0,Drug:0,Disease:0,kegg_Disease:0,Compound:0,Prediction:0};
		for (var item, i = 0; item = nodes[i++];) {
			lookup[item.data().Node_Type]++;
		}

		if(!lesslayer && !separated){
			var missingCircle;
			if(lookup.Protein === 0){
				missingCircle = orderOfNodeTypes[0];
				for (var x in orderOfNodeTypes){
					if(orderOfNodeTypes[x] > missingCircle)
						orderOfNodeTypes[x]--;
				}
			}
			if(lookup.Protein_N === 0){
				missingCircle = orderOfNodeTypes[1];
				for (var x in orderOfNodeTypes){
					if(orderOfNodeTypes[x] > missingCircle)
						orderOfNodeTypes[x]--;
				}
			}
			if((lookup.Pathway + lookup.kegg_Pathway) === 0){
				missingCircle = orderOfNodeTypes[2];
				for (var x in orderOfNodeTypes){
					if(orderOfNodeTypes[x] > missingCircle)
						orderOfNodeTypes[x]--;
				}
			}
			if(lookup.HPO === 0){
				missingCircle = orderOfNodeTypes[3];
				for (var x in orderOfNodeTypes){
					if(orderOfNodeTypes[x] > missingCircle)
						orderOfNodeTypes[x]--;
				}
			}
			if(lookup.Drug === 0){
				missingCircle = orderOfNodeTypes[4];
				for (var x in orderOfNodeTypes){
					if(orderOfNodeTypes[x] > missingCircle)
						orderOfNodeTypes[x]--;
				}
			}

			if((lookup.Disease + lookup.kegg_Disease) === 0){
				missingCircle = orderOfNodeTypes[5];
				for (var x in orderOfNodeTypes){
					if(orderOfNodeTypes[x] > missingCircle)
						orderOfNodeTypes[x]--;
				}
			}

			if((lookup.Compound + lookup.Prediction) === 0){
				missingCircle = orderOfNodeTypes[6];
				for (var x in orderOfNodeTypes){
					if(orderOfNodeTypes[x] > missingCircle)
						orderOfNodeTypes[x]--;
				}
			}
		}

		let startAngles = {};
		let incValofTypes = {};
		if(lesslayer){
			startAngles = {Protein:0,Pathway:90,Disease:180,Drug:270};
			incValofTypes = {Protein:2*Math.PI/(lookup.Protein+lookup.Protein_N),
								 Pathway:2*Math.PI/(lookup.Pathway+lookup.kegg_Pathway),
								 Disease:2*Math.PI/(lookup.Disease+lookup.kegg_Disease+lookup.HPO),
								 Drug:2*Math.PI/(lookup.Drug+lookup.Compound+lookup.Prediction)};
		}else{
			startAngles = {Protein_N:0,Protein:50,Pathway:100,HPO:150,Drug:205,Disease:255,Compound:310};
			incValofTypes = {Protein_N:2*Math.PI/lookup.Protein_N,
								 Protein:2*Math.PI/lookup.Protein,
								 Pathway:2*Math.PI/(lookup.Pathway+lookup.kegg_Pathway),
								 HPO:2*Math.PI/lookup.HPO,
								 Drug:2*Math.PI/lookup.Drug,
								 Compound:2*Math.PI/(lookup.Compound+lookup.Prediction),
								 Disease:2*Math.PI/(lookup.Disease+lookup.kegg_Disease)};
		}

		let centerx;
		let centery;
		let c;
		let radius;
		let theta; //= startAngle++;

		var crossBarPos = function crossBarPos(ele, i) {
			centerx = cy.width() / 2;
			centery = cy.height() / 2;
			if(lesslayer){
				switch (ele.data().Node_Type) {
					case 'Protein':
						radius = r * orderOfNodeTypes[0];
						theta = startAngles.Protein += incValofTypes.Protein;
					break;
					case 'Protein_N':
						radius = r * orderOfNodeTypes[0];
						theta = startAngles.Protein += incValofTypes.Protein;
					break;
					case 'Pathway':
						radius = r * orderOfNodeTypes[1];
						theta = startAngles.Pathway += incValofTypes.Pathway;
					break;
					case 'kegg_Pathway':
						radius = r * orderOfNodeTypes[1];
						theta = startAngles.Pathway += incValofTypes.Pathway;
					break;
					case 'HPO':
						radius = r * orderOfNodeTypes[2];
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'Disease':
						radius = r * orderOfNodeTypes[2];
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'kegg_Disease':
						radius = r * orderOfNodeTypes[2];
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'Drug':
						radius = r * orderOfNodeTypes[3];
						theta = startAngles.Drug += incValofTypes.Drug;
					break;
					case 'Compound':
						radius = r * orderOfNodeTypes[3];
						theta = startAngles.Drug += incValofTypes.Drug;
					break;
					case 'Prediction':
						radius = r * orderOfNodeTypes[3];
						theta = startAngles.Drug += incValofTypes.Drug;
					break;
				}
			}else{
				switch (ele.data().Node_Type) {
					case 'Protein':
						if(lookup.Protein === 1 && orderOfNodeTypes[0] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[0];
							theta = startAngles.Protein += incValofTypes.Protein;
						}
					break;
					case 'Protein_N':
						if(lookup.Protein_N === 1 && orderOfNodeTypes[1] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[1];
							theta = startAngles.Protein_N += incValofTypes.Protein_N;
						}
					break;
					case 'Pathway':
						if(lookup.Pathway === 1 && orderOfNodeTypes[2] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[2];
							theta = startAngles.Pathway += incValofTypes.Pathway;
						}
					break;
					case 'kegg_Pathway':
						if(lookup.Pathway === 1 && orderOfNodeTypes[2] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[2];
							theta = startAngles.Pathway += incValofTypes.Pathway;
						}
					break;
					case 'HPO':
						if(lookup.HPO === 1 && orderOfNodeTypes[3] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[3];
							theta = startAngles.HPO += incValofTypes.HPO;
						}
					break;
					case 'Drug':
						if(lookup.Drug === 1 && orderOfNodeTypes[4] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[4];
							theta = startAngles.Drug += incValofTypes.Drug;
						}
					break;
					case 'Disease':
						if(lookup.Disease === 1 && orderOfNodeTypes[5] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[5];
							theta = startAngles.Disease += incValofTypes.Disease;
						}
					break;
					case 'kegg_Disease':
						if(lookup.Disease === 1 && orderOfNodeTypes[5] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[5];
							theta = startAngles.Disease += incValofTypes.Disease;
						}
					break;
					case 'Compound':
						if(lookup.Compound === 1 && orderOfNodeTypes[6] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[6];
							theta = startAngles.Compound += incValofTypes.Compound;
						}
					break;
					case 'Prediction':
						if(lookup.Compound === 1 && orderOfNodeTypes[6] === 1){
							radius = 0;
							theta = 1;
						}else{
							radius = r * orderOfNodeTypes[6];
							theta = startAngles.Compound += incValofTypes.Compound;
						}
					break;
				}
			}
			//console.log(radius);
			let rx = radius * Math.cos( theta ); // gives x coordinat of point in the circle with provided angle (theta)
			let ry = radius * Math.sin( theta ); // gives y coordinat of point in the circle with provided angle (theta)
			let pos = {
			  x: centerx + rx,
			  y: centery + ry
			};

			return pos;

		};

		function centersOf7Circle(o,w,h,yshift=0){
			//switch(orderOfNodeTypes[1]){
			//console.log(yshift);
			var width = w;
			var height = h;
			var centerx;
			var centery;
			switch(o){
				case 1:
					centerx = (width / 28)*5;
					centery = ((height / 36)*9) - yshift;
				break;
				case 2:
					centerx = (width / 28)*14;
					centery = ((height / 36)*9) - yshift;
				break;
				case 3:
					centerx = (width / 28)*23;
					centery = ((height / 36)*9) - yshift;
				break;
				case 4:
					centerx = (width / 28)*5;
					centery = ((height / 36)*28) - yshift;
				break;
				case 5:
					centerx = (width / 28)*14;
					centery = ((height / 36)*28) - yshift;
				break;
				case 6:
					centerx = (width / 28)*23;
					centery = ((height / 36)*28) - yshift;
				break;
				case 7:
					centerx = (width / 28)*25;
					centery = ((height / 36)*27.5) - yshift;
				break;
			}
			console.log(centery);
			return {x:centerx,y:centery};
		}

		function centersOf4Circle(o,w,h){
			var width = w;
			var height = h;
			var centerx;
			var centery;
			switch(o){
				case 1:
					centerx = (width / 4)*1;
					centery = (height / 4)*1;
				break;
				case 2:
					centerx = (width / 4)*3;
					centery = (height / 4)*1;
				break;
				case 3:
					centerx = (width / 4)*1;
					centery = (height / 4)*3;
				break;
				case 4:
					centerx = (width / 4)*3;
					centery = (height / 4)*3;
				break;
			}
			return {x:centerx,y:centery};
		}

		if(!lesslayer && separated){
			console.log(orderOfNodeTypes);
			//var prot_y = (cy.height() / 36)*0.25;
			var prot_y = 0;
			var prot_counter = 1;
			var prot_width = cy.width() / (lookup.Protein+1);
			var yshift = 0;

			if(orderOfNodeTypes[0] > 6){
				yshift = prot_y*4;
				prot_y = (cy.height() / 36)*36;
			}else if(orderOfNodeTypes[0] > 3){
				yshift = prot_y*4;
				yshift = (cy.height() / 36)*1.5;
				prot_y = (cy.height() / 36)*17;
			}
			for (var x in orderOfNodeTypes){
				if(orderOfNodeTypes[x] > orderOfNodeTypes[0]){
					orderOfNodeTypes[x]--;
					//console.log('works');
				}
			}
			console.log(orderOfNodeTypes);
		}
		var crossBarPosSeparated = function crossBarPos(ele, i) {
			var width = cy.width();
			var height = cy.height();
			if(width<height){
				radius = width / 5;
			}else{
				radius = height / 5;
			}

			if(lesslayer){
				switch (ele.data().Node_Type) {
					case 'Protein':
						c = centersOf4Circle(orderOfNodeTypes[0],width,height);
						theta = startAngles.Protein += incValofTypes.Protein;
					break;
					case 'Protein_N':
						c = centersOf4Circle(orderOfNodeTypes[0],width,height);
						theta = startAngles.Protein += incValofTypes.Protein;
					break;
					case 'Pathway':
						c = centersOf4Circle(orderOfNodeTypes[1],width,height);
						theta = startAngles.Pathway += incValofTypes.Pathway;
					break;
					case 'kegg_Pathway':
						c = centersOf4Circle(orderOfNodeTypes[1],width,height);
						theta = startAngles.Pathway += incValofTypes.Pathway;
					break;
					case 'HPO':
						c = centersOf4Circle(orderOfNodeTypes[2],width,height);
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'Disease':
						c = centersOf4Circle(orderOfNodeTypes[2],width,height);
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'kegg_Disease':
						c = centersOf4Circle(orderOfNodeTypes[2],width,height);
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'Drug':
						c = centersOf4Circle(orderOfNodeTypes[3],width,height);
						theta = startAngles.Drug += incValofTypes.Drug;
					break;
					case 'Compound':
						c = centersOf4Circle(orderOfNodeTypes[3],width,height);
						theta = startAngles.Drug += incValofTypes.Drug;
					break;
					case 'Prediction':
						c = centersOf4Circle(orderOfNodeTypes[3],width,height);
						theta = startAngles.Drug += incValofTypes.Drug;
					break;
				}
			}else{
				//console.log(orderOfNodeTypes);
				/*console.log('width: '+width);
				console.log('prot_width: '+prot_width);
				console.log('prot_counter: '+prot_counter);
				console.log('number of prot: '+lookup.Protein);*/
				switch (ele.data().Node_Type) {
					case 'Protein':
						//c = centersOf7Circle(orderOfNodeTypes[0],width,height);

						//c = {x:prot_width*prot_counter, y:(height / 36)*0.25};
						c = {x:prot_width*prot_counter, y:prot_y};
						//console.log(c);
						prot_counter++;
						theta = startAngles.Protein += incValofTypes.Protein;
						/*
						console.log(lookup);
						console.log(lookup.Protein);
						*/

					break;
					case 'Protein_N':
						//if(orderOfNodeTypes[0] > 2)
							//yshift = (height / 36)*0.5;
						c = centersOf7Circle(orderOfNodeTypes[1],width,height,yshift);
						theta = startAngles.Protein_N += incValofTypes.Protein_N;
					break;
					case 'Pathway':
						c = centersOf7Circle(orderOfNodeTypes[2],width,height,yshift);
						theta = startAngles.Pathway += incValofTypes.Pathway;
					break;
					case 'kegg_Pathway':
						c = centersOf7Circle(orderOfNodeTypes[2],width,height,yshift);
						theta = startAngles.Pathway += incValofTypes.Pathway;
					break;
					case 'HPO':
						c = centersOf7Circle(orderOfNodeTypes[3],width,height,yshift);
						theta = startAngles.HPO += incValofTypes.HPO;
					break;
					case 'Drug':
						c = centersOf7Circle(orderOfNodeTypes[4],width,height,yshift);
						theta = startAngles.Drug += incValofTypes.Drug;
					break;
					case 'Disease':
						c = centersOf7Circle(orderOfNodeTypes[5],width,height,yshift);
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'kegg_Disease':
						c = centersOf7Circle(orderOfNodeTypes[5],width,height,yshift);
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'Compound':
						c = centersOf7Circle(orderOfNodeTypes[6],width,height,yshift);
						theta = startAngles.Compound += incValofTypes.Compound;
					break;
					case 'Prediction':
						c = centersOf7Circle(orderOfNodeTypes[6],width,height,yshift);
						theta = startAngles.Compound += incValofTypes.Compound;
					break;
				}
			}

			let rx = radius * Math.cos( theta );
			let ry = radius * Math.sin( theta );
			if(ele.data().Node_Type === 'Protein' && !lesslayer){
				rx = 0;
				ry = 0;
			}
			let pos = {
			  x: c.x + rx,
			  y: c.y + ry
			};

			return pos;

		};

		if (separated === 1){
			nodes.layoutPositions(layout, options, crossBarPosSeparated);
		}else{
			nodes.layoutPositions(layout, options, crossBarPos);
		}
    }
  }]);

  return Layout;
}();

module.exports = Layout;

/***/ })
/******/ ]);
});