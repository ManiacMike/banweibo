var st = {
	sset : function(a, b) {
		storage.set(a, b);
	},
	sget : function(a) {
		return storage.get(a);
	},
	sdel : function(a) {
		storage.del(a);
	},
	cset : function(a, b) {
		var c = [], _para = {};
		for ( var d = 0, _len = arguments.length; d < _len; d++) {
			c[d] = arguments[d];
		}
		;
		_para.exps = typeof (c[2]) != "undefined" ? Math.ceil(c[2]
				/ (3600 * 24)) : undefined;
		_para.name = c[0];
		_para.val = c[1];
		_para.path = c[3];
		_para.domain = c[4];
		_para.secure = c[5];
		storage.cookieStore.set(_para);
		return false;
	},
	cget : function(a) {
		return storage.cookieStore.get(a);
	},
	cdel : function(a) {
		storage.cookieStore.del(a);
	}
};
var storage = {
	_g: function (id){return document.getElementById(id);},
	_c: function (a){return document.createElement(a);},
	_domain : 'banweibo.com',
	_store : null,
	_init : false,
	init : function() {
		if (window.localStorage) {
			this._store = this.localStore;
		} else if (navigator.userAgent.indexOf('MSIE') >= 0) {
			this._store = this.ieStore;
		}
		this._store.init();
		this.cookieStore.init();
	},
	set : function(a, b) {
		this.checkInit();
		this._store.set({
			name : a,
			val : b
		});
	},
	get : function(a) {
		this.checkInit();
		return this._store.get(a);
	},
	del : function(a) {
		this.checkInit();
		this._store.del(a);
	},
	isInit : function() {
		this.checkInit();
		return this._store._init;
	},
	checkInit : function() {
		if (!this._init)
			this.init();
		if (!this._store.isInit())
			this._store.init();
	},
	localStore : {
		_init : true,
		init : function() {
		},
		get : function(a) {
			return localStorage.getItem(a);
		},
		set : function(a) {
			localStorage.setItem(a.name, a.val);
		},
		del : function(a) {
			localStorage.removeItem(a);
		},
		isInit : function() {
			return true;
		}
	},
	ieStore : {
		exps : 180,
		_init : false,
		init : function() {
			if (!this.isInit() && !storage._g("_ieStore")) {
				this.store = storage._c("INPUT"), this.store.type = "hidden",
						this.store.id = "_ieStore", this.store
								.addBehavior("#default#userData");
				var _headtag = document.getElementsByTagName("head")[0];
				_headtag.appendChild(this.store);
				this._init = true;
			} else if (storage._g("_ieStore")) {
				this.store = storage._g("_ieStore");
				this._init = true;
			}
			return this;
		},
		get : function(a) {
			try {
				this.store.load(a);
			} catch (e) {
				return null;
			}
			return this.store.getAttribute("__store__") || null;
		},
		set : function(a) {
			var b = a.name, _val = a.val, _exps = typeof (a.exps) != "undefined" ? a.exps
					: this.exps;
			var c = new Date();
			c.setDate(c.getDate() + _exps);
			this.store.load(b);
			this.store.expires = c.toUTCString();
			this.store.setAttribute("__store__", _val);
			this.store.save(b);
		},
		del : function(a) {
			this.set({
				name : a
			}, false, -1);
		},
		isInit : function() {
			return this._init;
		}
	},
	cookieStore : {
		_init : false,
		_exps : 180,
		_secure : "",
		init : function() {
			if (!this.isInit()) {
				this._domain = storage._domain;
				this._init = true;
			}
			;
			return this;
		},
		get : function(a) {
			if (!this._init)
				this.init();
			var b = document.cookie.split("; "), a = a + "=";
			for ( var c = 0, _len = b.length; c < _len; c++) {
				if (b[c].indexOf(a) != "-1") {
					try {
						return decodeURIComponent(b[c].replace(a, ""));
					} catch (e) {
						return unescape(b[c].replace(a, ""));
					}
				}
			}
			return null;
		},
		set : function(a) {
			if (!this._init)
				this.init();
			var b = new Date();
			var c = a.name, _val = a.val, _exps = typeof (a.exps) != "undefined" ? a.exps
					: this._exps, _domain = a.domain || this._domain, _path = a.path
					|| "/", _secure = a.secure || this._secure;
			b.setDate(b.getDate() + _exps);
			var d = c + "=" + escape(_val)
					+ (_exps ? ";expires=" + b.toUTCString() : "")
					+ (_path ? ";path=" + _path : "")
					+ (_domain ? ";domain=" + _domain : "")
					+ (_secure ? ";secure=" : "");
			document.cookie = d;
		},
		del : function(a) {
			if (!this._init)
				this.init();
			if (String.prototype.toLowerCase.apply(typeof (a)) == "string") {
				_name = a;
				a = {
					name : _name,
					val : ""
				};
			}
			a.exps = -1;
			a.secure = "";
			this.set(a);
		},
		isInit : function() {
			return this._init;
		}
	}
};