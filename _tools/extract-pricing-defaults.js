const fs = require('fs');
const path = require('path');

const dir = path.join(__dirname, '../template-parts/landing');
const map = {
	s04: { file: 'section-04-section_1969850638.php', id: 'section_1969850638', shared: true },
	s05: { file: 'section-05-section_1537602591.php', id: 'section_1537602591', shared: false },
	s06: { file: 'section-06-section_1462343327.php', id: 'section_1462343327', shared: false },
	s07: { file: 'section-07-section_245268808.php', id: 'section_245268808', shared: false },
	s08: { file: 'section-08-section_630925722.php', id: 'section_630925722', shared: false },
};

function between(html, start, end) {
	const i = html.indexOf(start);
	if (i === -1) return '';
	const j = html.indexOf(end, i + start.length);
	if (j === -1) return html.slice(i + start.length);
	return html.slice(i + start.length, j);
}

function stripTags(html) {
	return html.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
}

function allDetails(html) {
	const out = [];
	const re = /<details[\s\S]*?<\/details>/g;
	let m;
	while ((m = re.exec(html))) out.push(m[0]);
	return out;
}

function extractCard(block) {
	const header = between(block, 'vs-price-card-header', 'vs-price-card-tag');
	const divTexts = [...header.matchAll(/<div[^>]*>\s*([^<]+?)\s*<\/div>/g)].map((m) => m[1].trim());
	const spanPrice = header.match(/<span[^>]*>([^<]*)<\/span>/);
	const tagBlock = block.match(/<div class="vs-price-card-tag"[\s\S]*?<\/div>\s*<div class="vs-price-card-hero"/);
	const tag = tagBlock ? stripTags(tagBlock[0]) : '';
	const hero = between(block, 'vs-price-card-hero', '</div>\n</div>\n<details');
	const heroLines = [...hero.matchAll(/<span[^>]*>([\s\S]*?)<\/span>/g)].map((m) => stripTags(m[1]));
	const details = allDetails(block);
	const cta = block.match(/<a[^>]*href="([^"]*)"[^>]*>([\s\S]*?)<\/a>/);
	const variant = /c2410c|CÓ LƯU|LƯU TRỮ/i.test(block) && !/KHÔNG LƯU TRỮ/i.test(tag) ? 'storage' : 'grid';

	return {
		power: divTexts[0] || '',
		list_price: spanPrice ? spanPrice[1].trim() : '',
		brand_line: divTexts[divTexts.length - 1] || '',
		storage_tag: tag,
		package_label: heroLines[0] || '',
		package_price: heroLines[1] || '',
		package_note: heroLines[2] || '',
		config_html: details[0] || '',
		output_html: details[1] || '',
		btn_label: cta ? stripTags(cta[2]) : 'XEM CHI TIẾT CẤU HÌNH',
		btn_url: cta ? cta[1] : '#',
		variant,
	};
}

function phpString(s) {
	return "'" + String(s).replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "'";
}

function exportPhp(obj, indent = 1) {
	const pad = '\t'.repeat(indent);
	if (Array.isArray(obj)) {
		if (!obj.length) return 'array()';
		return (
			'array(\n' +
			obj.map((item) => pad + exportPhp(item, indent + 1) + ',').join('\n') +
			'\n' +
			'\t'.repeat(indent - 1) +
			')'
		);
	}
	if (obj && typeof obj === 'object') {
		const lines = Object.entries(obj).map(([k, v]) => {
			if (typeof v === 'boolean') return `${pad}'${k}' => ${v ? 'true' : 'false'},`;
			if (typeof v === 'string') return `${pad}'${k}' => ${phpString(v)},`;
			return `${pad}'${k}' => ${exportPhp(v, indent + 1)},`;
		});
		return 'array(\n' + lines.join('\n') + '\n' + '\t'.repeat(indent - 1) + ')';
	}
	return 'null';
}

const out = {};

for (const [prefix, meta] of Object.entries(map)) {
	const html = fs.readFileSync(path.join(dir, meta.file), 'utf8');
	const section = {
		section_id: meta.id,
		show_shared_header: meta.shared,
	};

	if (meta.shared) {
		const h2 = html.match(/<h2[^>]*>([\s\S]*?)<\/h2>/);
		section.pricing_badge = stripTags(between(html, '💰', '</div>'));
		section.pricing_title = h2 ? stripTags(h2[1]) : '';
		const sub = html.match(/<!-- SUBTITLE \(smaller\) -->[\s\S]*?<div style="margin: 0; font-size: clamp\(13px[\s\S]*?>([^<]+)</);
		section.pricing_subtitle = sub ? sub[1].trim() : '';
		const h3 = html.match(/<h3[^>]*>([\s\S]*?)<\/h3>/);
		section.tier_title = h3 ? stripTags(h3[1]) : '';
		const tierSub = html.match(/margin-top: 6px;[\s\S]*?>([^<]+)</);
		section.tier_subtitle = tierSub ? tierSub[1].trim() : '';
	} else {
		section.tier_title = stripTags(between(html, 'font-size: 28px;', '</span>'));
		section.tier_subtitle = stripTags(between(html, 'font-size: 14px;', '</span>'));
	}

	const parts = html.split('vs-price-card-shell');
	section.cards = parts.slice(1).map((chunk) => extractCard(chunk.slice(0, 12000)));
	out[prefix] = section;
}

const php = `<?php
/**
 * Default pricing sections 04–08 (seed for ACF repeaters).
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return ${exportPhp(out)};
`;

fs.writeFileSync(path.join(__dirname, '../inc/landing-solar-pricing-defaults.php'), php);
console.log('Wrote landing-solar-pricing-defaults.php', 's04 cards:', out.s04.cards[0].power);
