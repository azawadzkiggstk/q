import MicroModal from 'micromodal';

export default function ( { attributes } ) {
	document.addEventListener( 'DOMContentLoaded', () => {
		new VideoButton(
			document.querySelector( `.wp-block-${ attributes.blockId }` ),
			attributes
		).init();
	} );
}

class VideoButton {
	constructor( element, attributes ) {
		this.element = element;
		this.attributes = attributes;
		this.button = this.element.querySelector(
			'.wp-block-statik-video-button__link'
		);
		this.blockId = this.attributes.blockId;
		this.modalUrl = this.attributes.blockAtts.url;
		this.modalContainerElement = null;
	}

	init() {
		this.registerEffects();
	}

	registerEffects() {
		this.button.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			this.appendModal();
			this.openModal();
		} );
	}

	appendModal() {
		if ( this.modalContainerElement ) {
			return;
		}

		const modalHtml = this.getModalHtml();

		const modalTemplate = document.createElement( 'template' );

		modalTemplate.innerHTML = modalHtml.trim();
		this.modalContainerElement = modalTemplate.content.firstChild;
		document.body.appendChild( this.modalContainerElement );
	}

	openModal() {
		const modalConfig = this.getModalConfig();
		MicroModal.show( `modal-video-${ this.blockId }`, modalConfig );
	}

	destroyModal() {
		if ( ! this.modalContainerElement ) {
			return;
		}
		this.modalContainerElement.remove();
		this.modalContainerElement = null;
	}

	getModalHtml() {
		return `
		<div id="modal-video-${ this.blockId }" class="modal micromodal-slide" aria-hidden="true">
			<div class="modal__overlay">
				<div class="modal__content"> 
					<button class="modal__close" aria-label="Close modal" data-micromodal-close></button> 
					<iframe id="modal-iframe-${ this.blockId }" width="560" height="315" src="${ this.modalUrl }" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
			</div>
		</div>
		`;
	}

	getModalConfig() {
		return {
			disableScroll: true,
			awaitCloseAnimation: true,
			onClose: ( modal ) => {
				const self = this;
				modal.addEventListener(
					'animationend',
					function handler() {
						self.destroyModal();
						modal.removeEventListener(
							'animationend',
							handler,
							false
						);
					},
					false
				);
			},
		};
	}
}
