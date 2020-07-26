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
  sizesoftypes: undefined,
  divideByProperty: undefined,
  //orderOfNodeTypes: [6,5,4,3,2,1],
  orderOfNodeTypes: undefined,
  radius: 100,
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
		var cy = options.cy;
		var r = options.radius;
		var lesslayer = options.lesslayer;
		var eles = options.eles;
		var nodes = eles.nodes();
		var edges = eles.edges();

		// setting bounding box.
		let bb = { x1: 0, y1: 0, w: cy.width(), h: cy.height() };

		var lookup = {Protein_N:0,Protein:0,Pathway:0,kegg_Pathway:0,HPO:0,Drug:0,Disease:0,kegg_Disease:0,Compound:0,Prediction:0};
		for (var item, i = 0; item = nodes[i++];) {
			lookup[item.data().Node_Type]++;
		}

		let startAngle = 0;		
		let startAngles = {};
		let incValofTypes = {};
		if(lesslayer){

			startAngles = {Protein:0,Pathway:0,Disease:0,Drug:0};
			incValofTypes = {Protein:2*Math.PI/(lookup.Protein+lookup.Protein_N),
								 Pathway:2*Math.PI/(lookup.Pathway+lookup.kegg_Pathway),
								 Disease:2*Math.PI/(lookup.Disease+lookup.kegg_Disease+lookup.HPO),
								 Drug:2*Math.PI/(lookup.Drug+lookup.Compound+lookup.Prediction)};
		}else{
			startAngles = {Protein_N:0,Protein:0,Pathway:0,HPO:0,Drug:0,Disease:0,Compound:0};
			incValofTypes = {Protein_N:2*Math.PI/lookup.Protein_N,
								 Protein:2*Math.PI/lookup.Protein,
								 Pathway:2*Math.PI/(lookup.Pathway+lookup.kegg_Pathway),
								 HPO:2*Math.PI/lookup.HPO,
								 Drug:2*Math.PI/lookup.Drug,
								 Compound:2*Math.PI/(lookup.Compound+lookup.Prediction),
								 Disease:2*Math.PI/(lookup.Disease+lookup.kegg_Disease)};
		}
		
		let centerx = cy.width() / 2;
		let centery = cy.height() / 2;
		let radius;

		var crossBarPos = function crossBarPos(ele, i) {

			let theta; //= startAngle++;
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
						radius = r * orderOfNodeTypes[0];
						theta = startAngles.Protein += incValofTypes.Protein;
					break;
					case 'Protein_N':
						radius = r * orderOfNodeTypes[1];
						theta = startAngles.Protein_N += incValofTypes.Protein_N;
					break;
					case 'Pathway':
						radius = r * orderOfNodeTypes[2];
						theta = startAngles.Pathway += incValofTypes.Pathway;
					break;
					case 'kegg_Pathway':
						radius = r * orderOfNodeTypes[2];
						theta = startAngles.Pathway += incValofTypes.Pathway;
					break;
					case 'HPO':
						radius = r * orderOfNodeTypes[3];
						theta = startAngles.HPO += incValofTypes.HPO;
					break;
					case 'Drug':
						radius = r * orderOfNodeTypes[4];
						theta = startAngles.Drug += incValofTypes.Drug;
					break;
					case 'Disease':
						radius = r * orderOfNodeTypes[5];
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'kegg_Disease':
						radius = r * orderOfNodeTypes[5];
						theta = startAngles.Disease += incValofTypes.Disease;
					break;
					case 'Compound':
						radius = r * orderOfNodeTypes[6];
						theta = startAngles.Compound += incValofTypes.Compound;
					break;
					case 'Prediction':
						radius = r * orderOfNodeTypes[6];
						theta = startAngles.Compound += incValofTypes.Compound;
					break;
				}
			}
			//console.log(radius);
			let rx = radius * Math.cos( theta );
			let ry = radius * Math.sin( theta );
			let pos = {
			  x: centerx + rx,
			  y: centery + ry
			};

			return pos;
			
		};

      nodes.layoutPositions(layout, options, crossBarPos);
    }
  }]);

  return Layout;
}();

module.exports = Layout;

/***/ })
/******/ ]);
});