const fs = require('fs');
const path = require('path');

const html = fs.readFileSync(
	path.join(__dirname, '../template-parts/landing/part-brands-block.php'),
	'utf8'
);

function stripTags(s) {
	return s.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
}

const titleBlock = html.match(/vs-brands-title-block__heading[\s\S]*?<\/div>/)[0];
const lines = [...titleBlock.matchAll(/<span>([\s\S]*?)<\/span>/g)].map((m) => stripTags(m[1]));
const subtitle = stripTags(html.match(/vs-brands-subtitle-block__text">([^<]*)</)[1]);

const cards = [];
const parts = html.split('vs-brand-card-title');
for (let i = 1; i < parts.length; i++) {
	const chunk = parts[i];
	const title = stripTags(chunk.match(/>([^<]+)</)[1]);
	const body = chunk.split('vs-brand-card-body')[1] || '';
	const bullets = [...body.matchAll(/vs-brand-card-line">([\s\S]*?)<\/div>/g)].map((m) => stripTags(m[1]));
	const cta = chunk.match(/<a[^>]*href="([^"]*)"[^>]*>[\s\S]*?<span[^>]*>([\s\S]*?)<\/span>/);
	cards.push({
		title,
		bullet_1: bullets[0] || '',
		bullet_2: bullets[1] || '',
		bullet_3: bullets[2] || '',
		btn_label: cta ? stripTags(cta[2]) : 'XEM CHI TIẾT',
		btn_url: cta ? cta[1] : '#',
	});
}

function phpString(s) {
	return "'" + String(s).replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "'";
}

function row(obj, indent) {
	const pad = '\t'.repeat(indent);
	return (
		'array(\n' +
		Object.entries(obj)
			.map(([k, v]) => `${pad}\t'${k}' => ${phpString(v)},`)
			.join('\n') +
		`\n${pad})`
	);
}

const php = `<?php
/**
 * Default brands block (section 10).
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'title_line_1' => ${phpString(lines[0] || '')},
	'title_line_2' => ${phpString(lines[1] || '')},
	'subtitle'     => ${phpString(subtitle)},
	'cards'        => array(
${cards.map((c) => '\t\t' + row(c, 2) + ',').join('\n')}
	),
);
`;

fs.writeFileSync(path.join(__dirname, '../inc/landing-solar-brands-defaults.php'), php);
console.log('brands cards', cards.length);
