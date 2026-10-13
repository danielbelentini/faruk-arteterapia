/**
 * Copia os arquivos do Swiper de node_modules para assets/vendor/swiper/,
 * para que o tema carregue tudo localmente (sem CDN).
 *
 * Roda automaticamente depois de `npm install` (postinstall) e também
 * pode ser executado manualmente com `npm run vendor`.
 */
const fs = require( 'fs' );
const path = require( 'path' );

const root = path.resolve( __dirname, '..' );
const sourceDir = path.join( root, 'node_modules', 'swiper' );
const targetDir = path.join( root, 'assets', 'vendor', 'swiper' );

const files = [ 'swiper-bundle.min.js', 'swiper-bundle.min.css' ];

if ( ! fs.existsSync( sourceDir ) ) {
	console.warn( '[vendor] node_modules/swiper não encontrado. Rode "npm install" primeiro.' );
	process.exit( 0 );
}

fs.mkdirSync( targetDir, { recursive: true } );

let missing = false;

files.forEach( ( file ) => {
	const from = path.join( sourceDir, file );
	const to = path.join( targetDir, file );

	if ( ! fs.existsSync( from ) ) {
		console.warn( `[vendor] Arquivo não encontrado na versão instalada do Swiper: ${ file }` );
		missing = true;
		return;
	}

	fs.copyFileSync( from, to );
	console.log( `[vendor] Copiado: assets/vendor/swiper/${ file }` );
} );

if ( missing ) {
	console.warn( '[vendor] Verifique os nomes dos arquivos na versão instalada do Swiper.' );
}
