package assets {
	import flash.display.MovieClip;
	import flash.display.SimpleButton;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import com.greensock.TweenMax;
	
	public class Conexoes extends MovieClip {
		public var btnCampanhas : SimpleButton;
		public var btnConexoes : SimpleButton;
		public var btnContexto : SimpleButton;
		private var _evt : String;

		public function Conexoes() {
			stop();
			this.visible = false;
		}

		public function show() : void {
			this.visible = true;
			
			btnCampanhas.addEventListener(MouseEvent.CLICK, onBtnClick);			
			btnConexoes.addEventListener(MouseEvent.CLICK, onBtnClick);
			btnContexto.addEventListener(MouseEvent.CLICK, onBtnClick);				
			
			this.gotoAndPlay(1);
		}
		
		public function finalizado() : void {
			dispatchEvent(new TriboEvent(this._evt)); 
		}
		
		public function finaliza(evt : String) : void {
			this._evt = evt;
			
			TweenMax.to(this, 2, { frame : 1, onComplete:function() {
					finalizado();
				}
			} );
		}
		
		private function onBtnClick(e:MouseEvent) : void {
			btnCampanhas.removeEventListener(MouseEvent.CLICK, onBtnClick);
			btnConexoes.removeEventListener(MouseEvent.CLICK, onBtnClick);
			btnContexto.removeEventListener(MouseEvent.CLICK, onBtnClick);
			
			var evt : String = TriboEvent.CONTEXTO;
			if (e.currentTarget == btnCampanhas) {
				evt = TriboEvent.CAMPANHAS;
			} else if (e.currentTarget == btnConexoes) {
				evt = TriboEvent.CONEXOES;
			}
			
			finaliza(evt);
		}
	}	
}