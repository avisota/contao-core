/*
Class: Slider
        Creates a slider with two elements: a knob and a container. Returns the values.
Note:
        The Slider requires an XHTML doctype.
Arguments:
        element - the knob container
        knob - the handle
        options - see Options below
        maxknob - an optional maximum slider handle
Options:
		start - the minimum value for your slider.
		end - the maximum value for your slider.
        mode - either 'horizontal' or 'vertical'. defaults to horizontal.
        offset - relative offset for knob position. default to 0.
        knobheight - positions the max slider knob
		snap - whether the slider will slide in steps 
		numsteps - number of slide steps 
Events:
        onChange - a function to fire when the value changes.
        onComplete - a function to fire when you're done dragging.
        onTick - optionally, you can alter the onTick behavior, for example displaying an effect of the knob moving to the desired position.
                Passes as parameter the new position.
*/
var Slider = new Class({
	options: {
		onChange: Class.empty,
		onComplete: Class.empty,
		onTick: function(pos){
			this.moveKnob.setStyle(this.p, pos);			
		},
		start: 0,
		end: 100,
		offset: 0,
		knobheight: 20,
		knobwidth: 14,
		mode: 'horizontal',
		clip_w:0, 
		clip_l:0,
		isinit:true,
		snap: false,
		range: false,
		numsteps:null
	},
    initialize: function(el, knob,bkg, options, maxknob) {
		this.setOptions(options);
		this.element = $(el);
		this.knob = $(knob);
		this.previousChange = this.previousEnd = this.step = -1;
		this.bkg = $(bkg);
		if(this.options.steps==null){
			this.options.steps = this.options.end - this.options.start;
		}
		if(maxknob!=null)
			this.maxknob = $(maxknob);
		//else
		//	this.element.addEvent('mousedown', this.clickedElement.bindWithEvent(this));
		var mod, offset;
		switch(this.options.mode){
			case 'horizontal':
				this.z = 'x';
				this.p = 'left';
				mod = {'x': 'left', 'y': false};
				offset = 'offsetWidth';
				break;
			case 'vertical':
				this.z = 'y';
				this.p = 'top';
				mod = {'x': false, 'y': 'top'};
				offset = 'offsetHeight';
		}
		this.max = this.element[offset] - this.knob[offset] + (this.options.offset * 2);
		this.half = this.knob[offset]/2;
		this.full = this.element[offset] - this.knob[offset] + (this.options.offset * 2);
		this.min = $chk(this.options.range[0]) ? this.options.range[0] : 0;
		this.getPos = this.element['get' + this.p.capitalize()].bind(this.element);
		this.knob.setStyle('position', 'relative').setStyle(this.p, - this.options.offset);

		this.range = this.max - this.min;
		this.steps = this.options.steps || this.full;
		this.stepSize = Math.abs(this.range) / this.steps;
		this.stepWidth = this.stepSize * this.full / Math.abs(this.range) ;
		

		if(maxknob != null) {
			this.maxPreviousChange = -1;
			this.maxPreviousEnd = -1;
			this.maxstep = this.options.end;
			this.maxknob.setStyle('position', 'relative').setStyle(this.p, + this.max - this.options.offset).setStyle('bottom', this.options.knobheight);
		}
		var lim = {};
		//status = this.z
		lim[this.z] = [- this.options.offset, this.max - this.options.offset];
		//lim[this.z] = [100, this.max - this.options.offset];

		this.drag = new Drag(this.knob, {
			limit: lim,
			modifiers: mod,
			snap: 0,
			onStart: function(){
					this.draggedKnob();
			}.bind(this),
			onDrag: function(){
					this.draggedKnob();
			}.bind(this),
			onComplete: function(){
					this.draggedKnob();
					this.end();
			}.bind(this)
		});
		if(maxknob != null) {  
			this.maxdrag = new Drag(this.maxknob, {
				limit: lim,
				modifiers: mod,
				snap: 0, 
				onStart: function(){
					this.draggedKnob(1);
				}.bind(this),
				onDrag: function(){
					this.draggedKnob(1);
				}.bind(this),
				onComplete: function(){
					this.draggedKnob(1);
					this.end();
				}.bind(this)
			});		
		}

		if (this.options.snap) {
			//this.drag.options.grid = Math.ceil(this.stepWidth);
			this.drag.options.grid = (this.full)/this.options.numsteps ;
			this.drag.options.limit[this.z][1] = this.full;
			//this.drag.options.grid = this.drag.options.grid - (this.knob[offset]/this.options.numsteps);
			status = "GRID - " + this.drag.options.grid  + "  , full = " + this.full// DEBUG

		}
		if (this.options.initialize) this.options.initialize.call(this);
    },
	setMin: function(stepMin){
		this.step = stepMin.limit(this.options.start, this.options.end);
		this.checkStep();
		this.end();
		this.moveKnob = this.knob;
		this.bkg.style.clip = "rect(0px "+  (parseInt(this.toPosition(this.step)) +3) + "px 10px 0px)";
		status =this.bkg.style.clip + "  vl= " + parseInt(this.toPosition(this.step)) ; //Debug
		this.fireEvent('onTick', this.toPosition(this.step));
		return this;
	},
	setMax: function(stepMax){
		this.maxstep = stepMax.limit(this.options.start, this.options.end);
		this.checkStep(1);
		this.end();
		this.moveKnob = this.maxknob;
		var w= Math.abs(this.toPosition(this.step)- this.toPosition(this.maxstep)) + 3 ;
		var r = parseInt(this.clip_l + w); 
		this.bkg.style.clip = "rect(0px "+  r + "px 10px "+ this.clip_l + "px)";

		this.fireEvent('onTick', this.toPosition(this.maxstep));
		// For Init Only 
		if(this.options.isinit){
			var lim = {}; var mi,mx;
			mi = - this.options.offset; 
			mx= parseInt(this.maxknob.getStyle('left')) - this.options.offset-4 ;
			lim[this.z] = [mi, mx];
			this.drag.options.limit = lim;
			this.options.isinit = false;
		}
		return this; 
	},
	clickedElement: function(event){
		var position = event.page[this.z] - this.getPos() - this.half;
		position = position.limit(-this.options.offset, this.max -this.options.offset);

		this.step = this.toStep(position);

		//this.moveKnob = this.knob;
		this.bkg.style.clip = "rect(0px "+  (parseInt(this.toPosition(this.step)) +3) + "px 10px 0px)"  
		//status =this.bkg.style.clip; //Debug
		this.checkStep();
		this.end();
		this.fireEvent('onTick', position);
	},

	draggedKnob: function(mx){
		var lim = {}; var mi,mx;
		if(mx==null) {
			this.step = this.toStep(this.drag.value.now[this.z]);	 
			this.checkStep();
		}else {
			this.maxstep = this.toStep(this.maxdrag.value.now[this.z]); 
			this.checkStep(1);
		}
	},
	checkStep: function(mx){
		var lim = {}; var mi,mx;
		var limm = {};
		if(mx==null) {if (this.previousChange != this.step){this.previousChange = this.step;}}
		else {if (this.maxPreviousChange != this.maxstep){this.maxPreviousChange = this.maxstep;}}

		if(this.maxknob!=null) {

			mi = - this.options.offset; 
			mx= parseInt(this.maxknob.getStyle('left')) - this.options.offset-4 ;
			//mx= parseInt(this.maxknob.getStyle('left')) - this.options.offset ;
			lim[this.z] = [mi, mx];
			this.drag.options.limit = lim;
		

			mi = parseInt(this.knob.getStyle('left'))-this.options.offset+22; 
			//mi = parseInt(this.knob.getStyle('left'))-this.options.offset; 
			
			mx= this.max - this.options.offset;
			limm[this.z] = [mi, mx];
			this.maxdrag.options.limit = limm; 

			if(this.step < this.maxstep){
				this.fireEvent('onChange', { minpos: this.step, maxpos: this.maxstep });
				//this.clip_l = parseInt(this.knob.getStyle('left'));
			}
			else{
				this.fireEvent('onChange', { minpos: this.maxstep, maxpos: this.step });
				//this.clip_l = (parseInt(this.maxknob.getStyle('left')) + 10) ;
			}	
			this.clip_l = parseInt(this.knob.getStyle('left')) + 10;
			//var w = Math.abs(parseInt(this.knob.getStyle('left')) - parseInt(this.maxknob.getStyle('left'))) + 3;	
			var w = Math.abs(parseInt(this.knob.getStyle('left')) - parseInt(this.maxknob.getStyle('left')));
			//if(w > 3) w = w+3;
			
			var r = parseInt(this.clip_l + w); 
			this.bkg.style.clip = "rect(0px "+  r + "px 10px "+ this.clip_l + "px)"  
			//status =this.bkg.style.clip  + " w= " + w //Debug

		}else {  
			this.fireEvent('onChange', this.step);
			this.bkg.style.clip = "rect(0px "+  (parseInt(this.drag.value.now[this.z]) +3)  + "px 10px 0px)"  

		}
	},
	end: function(){
		if (this.previousEnd !== this.step || (this.maxknob != null && this.maxPreviousEnd != this.maxstep)) {
			this.previousEnd = this.step;
			if(this.maxknob != null) {
				this.maxPreviousEnd = this.maxstep;
				if(this.step < this.maxstep)
					this.fireEvent('onComplete', { minpos: this.step + '', maxpos: this.maxstep + '' });
				else    
					this.fireEvent('onComplete', { minpos: this.maxstep + '', maxpos: this.step + '' });
			}else{  
				this.fireEvent('onComplete', this.step + '');
			}
		}
	},
	
	toStep: function(position){
		return Math.round((position + this.options.offset) / this.max * this.options.steps) + this.options.start;
	},

	toPosition: function(step){
		return (this.max * step / this.options.steps) - (this.max * this.options.start / this.options.steps) - this.options.offset;
	}

});

Slider.implement(new Events);
Slider.implement(new Options);