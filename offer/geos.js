// geos.js
const COUNTRY_MAP = {
    // América
    // Europa
    "al": "Albania / Shqipëria",
    "al_sq": "Shqipëria", // Albanés
    "de": "Deutschland", // Alemán
    "ad": "Andorra",
    "ad_ca": "Andorra", // Catalán
    "am": "Հայաստան", // Armenia (Armenio)
    "at": "Österreich", // Austria (Alemán)
    "be": "Belgique", // Francés
    "be_nl": "België", // Neerlandés
    "be_de": "Belgien", // Alemán
    "by": "Беларусь", // Bielorrusia (Bielorruso)
    "ba": "Bosna i Hercegovina", // Bosnio
    "ba_bs": "Bosna i Hercegovina", // Bosnio
    "ba_hr": "Bosna i Hercegovina", // Croata
    "ba_sr": "Босна и Херцеговина", // Serbio
    "bg": "България", // Bulgaria (Búlgaro)
    "cy": "Κύπρος", // Chipre (Griego)
    "cy_tr": "Kıbrıs", // Turco
    "hr": "Hrvatska", // Croacia (Croata)
    "dk": "Danmark", // Dinamarca (Danés)
    "sk": "Slovensko", // Eslovaquia (Eslovaco)
    "si": "Slovenija", // Eslovenia (Esloveno)
    "es": "España",
    "es_ca": "Espanya", // Catalán
    "es_eu": "Espainia", // Euskera
    "es_gl": "España", // Gallego
    "ee": "Eesti", // Estonia (Estonio)
    "fi": "Suomi", // Finlandia (Finés)
    "fi_sv": "Finland", // Sueco
    "fr": "France",
    "ge": "საქართველო", // Georgia (Georgiano)
    "gr": "Ελλάδα", // Grecia (Griego)
    "hu": "Magyarország", // Hungría (Húngaro)
    "ie": "Ireland / Éire",
    "ie_ga": "Éire", // Irlandés
    "is": "Ísland", // Islandia (Islandés)
    "it": "Italia",
    "lv": "Latvija", // Letonia (Letón)
    "li": "Liechtenstein",
    "lt": "Lietuva", // Lituania (Lituano)
    "lu": "Luxembourg", // Francés
    "lu_de": "Luxemburg", // Alemán
    "lu_lb": "Lëtzebuerg", // Luxemburgués
    "mk": "Северна Македонија", // Macedonia (Macedonio)
    "mt": "Malta",
    "mt_mt": "Malta", // Maltés
    "md": "Moldova", // Rumano
    "md_ru": "Молдова", // Ruso
    "mc": "Monaco",
    "me": "Црна Гора", // Montenegro (Montenegrino)
    "no": "Norge", // Noruego Bokmål
    "no_nn": "Noreg", // Noruego Nynorsk
    "nl": "Nederland", // Países Bajos (Neerlandés)
    "nl_fy": "Nederlân", // Frisón
    "pl": "Polska", // Polonia (Polaco)
    "pt": "Portugal",
    "gb": "United Kingdom",
    "gb_ga": "Ríocht Aontaithe", // Irlandés
    "gb_gd": "Rìoghachd Aonaichte", // Gaélico escocés
    "gb_cy": "Teyrnas Unedig", // Galés
    "cz": "Česko", // República Checa (Checo)
    "ro": "România", // Rumania (Rumano)
    "ru": "Россия", // Rusia (Ruso)
    "sm": "San Marino",
    "rs": "Србија", // Serbia (Serbio)
    "se": "Sverige", // Suecia (Sueco)
    "se_fi": "Ruotsi", // Finlandés
    "se_sm": "Svierik", // Sami
    "ch": "Schweiz", // Alemán
    "ch_fr": "Suisse", // Francés
    "ch_it": "Svizzera", // Italiano
    "ch_rm": "Svizra", // Romanche
    "tr": "Türkiye", // Turquía (Turco)
    "ua": "Україна", // Ucrania (Ucraniano)
    "va": "Città del Vaticano"
};

const DEFAULT_GEO = "de"; // Deutschland als Standard

// Función para obtener el nombre del país
function getCountryName(geoCode) {
    // Primero intentamos con el código exacto
    if (COUNTRY_MAP[geoCode]) {
        return COUNTRY_MAP[geoCode].split('/')[0].trim(); // Tomamos el primer nombre si hay múltiples
    }

    // Si no encontramos, buscamos por código base (sin sufijo de idioma)
    const baseCode = geoCode.split('_')[0];
    if (COUNTRY_MAP[baseCode]) {
        return COUNTRY_MAP[baseCode].split('/')[0].trim();
    }

    // Si todo falla, devolvemos el valor por defecto
    return COUNTRY_MAP[DEFAULT_GEO];
}

// Todos los geos están permitidos
const ALLOWED_GEOS = Object.keys(COUNTRY_MAP);