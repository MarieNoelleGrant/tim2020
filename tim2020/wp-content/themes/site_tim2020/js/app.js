define(["require","exports","./Visionneuses","./Menu","./Filtres","./Validations","./GestionVideo"],function(e,s,n,i,a,o,t){"use strict";Object.defineProperty(s,"__esModule",{value:!0}),new i.Menu,$("main").hasClass("programme")&&(new n.Visionneuses,new t.GestionVideo),$("main").hasClass("realisations")&&new a.Filtres,($("main").hasClass("contact")||$("main").hasClass("stages"))&&new o.Validations,$("window").ready(function(){var e=$(document);e.bind("scroll",function(){0!==e.scrollLeft()&&e.scrollLeft(0)})})});