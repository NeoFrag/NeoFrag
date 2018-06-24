<svg style="display: none;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
	<symbol id="logo" viewBox="410 410 1410 1010" preserveAspectRatio="xMinYMin meet">
		<defs>
			<path id="square" d="M411 535h397.9v397.9H411z"/>
			<path id="F" d="M1065.1 676.3l176-64 18.1 49.6-104.5 38 14.6 40.2 89.2-32.6 17 46.5-89.2 32.6 34.2 94-71.4 26-84-230.3z"/>

			<clipPath id="square_mask">
				<use xlink:href="#square"/>
			</clipPath>

			<clipPath id="F_mask">
				<use xlink:href="#F"/>
			</clipPath>
		</defs>

		<!-- Square shadow -->
		<image width="548" height="548" xlink:href="<?php echo image('logo_shadow1.png', $this->theme('default')) ?>" transform="translate(361 485)"/>

		<!-- Square -->
		<use class="colored" xlink:href="#square"/>

		<g clip-path="url(#square_mask)">
			<!-- Square effect -->
			<image width="720" height="720" xlink:href="<?php echo image('logo_effect.png', $this->theme('default')) ?>" transform="translate(411 535) scale(.5528)"/>

			<!-- N shadow -->
			<image width="296" height="306" xlink:href="<?php echo image('logo_shadow3.png', $this->theme('default')) ?>" transform="translate(514 654)"/>
		</g>

		<!-- NeoFrag shadow -->
		<image width="1467" height="642" xlink:href="<?php echo image('logo_shadow2.png', $this->theme('default')) ?>" transform="translate(336 460)"/>

		<!-- N -->
		<path class="text" d="M682.9 794.9l-92.2-135.6H520l-.2 245h71.3V769.8l91.8 134.5h71.5v-245h-71.5z"/>

		<!-- E -->
		<path class="text" d="M765.6 720.4h152.3v39.2h-95.3v29.3H911v37.4h-88.4v36.2h98v41.6h-155V720.4z"/>

		<!-- F -->
		<use class="text" xlink:href="#F"/>

		<!-- O shadow -->
		<g clip-path="url(#F_mask)">
			<image width="371" height="370" xlink:href="<?php echo image('logo_shadow4.png', $this->theme('default')) ?>" transform="translate(846 639)"/>
		</g>

		<!-- O -->
		<path class="text" d="M921.3 809.4c0-30 8.3-53.4 25.1-70.2 16.6-16.6 40-25.1 69.8-25.1 30.6 0 54.3 8.1 70.7 24.6 16.6 16.5 24.8 39.4 24.8 69.1 0 21.5-3.6 39.1-10.9 52.8-7.2 13.7-17.7 24.4-31.3 32-13.7 7.6-30.7 11.4-51.2 11.4-20.8 0-38-3.3-51.5-9.9-13.6-6.7-24.6-17-33.1-31.3-8.2-14-12.4-31.9-12.4-53.4zm56.8.1c0 18.6 3.4 31.8 10.3 40 6.9 8.1 16.3 12.1 28.2 12.1 12.1 0 21.7-4 28.4-11.9 6.7-8 9.9-22.2 9.9-42.7 0-17.4-3.4-30-10.5-38s-16.5-11.9-28.4-11.9c-11.4 0-20.6 4-27.7 12.1-6.8 8.3-10.2 21.7-10.2 40.3z"/>

		<!-- RAG -->
		<path class="text" d="M1670.8 699l-82.5 30 13 36 35.6-13.2 6 16.3c-5.4 6.7-10.7 11.9-15.4 15.6-4.7 3.8-10.1 6.7-16.1 8.9-12.7 4.7-23.9 4.2-33.8-1.3-9.9-5.4-18.3-17.5-25.1-36.2-6.3-17.5-7.6-31.7-3.6-42.3 4-10.7 11.8-18.1 23.5-22.4 8-2.7 15-3.4 21.3-1.8s11.8 5.2 16.3 11l47.9-27.9c-7.1-9.9-15.2-17.4-24.1-22.4-8.9-5.1-18.8-7.6-29.5-7.6s-25.5 3.6-44.7 10.5c-19.7 7.1-34.5 15.7-44.1 25.5-12.3 12.7-19.9 27.3-22.6 44.1-1.7 11-1.4 22.5 1 34.5l-48-56.1-58.2 21.2-1.4 141.7-1.8-1.7c-2.2-2-6-4.7-11.4-8-5.2-3.3-9.2-5.2-11.8-6-3.8-1.1-9.2-1.4-16.3-1.3 7.2-4.7 12.7-9.4 16.3-13.7 5.6-6.9 9-14.5 10.7-23 1.6-8.3.5-17.4-2.9-26.9-4.2-11-10.1-19.4-18.3-25.1-8.1-5.8-17-8.5-26.8-8.1-9.8.4-23 3.6-39.4 9.6l-89 32.4 62.9 172.7 53.7-19.4-25.5-70.2 4.7-1.6c4.9-1.8 9.6-2 14.5-.7 3.6.9 8.3 4.2 14.5 9.8l47.7 42.3 17.5-6.4v.1L1440 898l-2-31.7 60.6-22.1L1518 870l55.9-20.4-9.5-11.1c1.5.3 3 .6 4.5.8 15.4 2.4 33.5-.4 53.9-7.8 16.6-6 30.4-13.2 41.1-21.5s21.9-20.8 33.8-37.3l-26.9-73.7zM1317 817.5c-1.3 3.6-3.4 6.5-6.7 8.5-6.3 4.2-10.9 6.7-13.2 7.6l-22.4 8.1-13-35.1 23.5-8.5c9.8-3.4 16.8-4.3 21.5-2.7 4.5 1.6 8 5.2 9.9 10.9 1.5 3.8 1.7 7.4.4 11.2zm119.4 7.2l-3.8-68.9 41.6 55.2-37.8 13.7z"/>
	</symbol>
</svg>
