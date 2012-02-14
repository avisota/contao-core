<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['invisible']       = array('Usynlig ',' Dette element vil ikke blive vist.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['type']            = array('Elementtype', 'Vælg venligst elementtypen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area']            = array('Area', 'Please choose the area the content element should be showed in.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['headline']        = array('Overskrift', 'Hvis du indtaster en overskrift, vil den blive vist over indholdselementet.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['text']            = array('Tekst', 'Indtast venligst teksten (du kan anvende HTML-mærkater).');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['definePlain']     = array('Plain Text Over-Ride','Enter the plain text, rather than let it automatically create HTML from the text.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['plain']           = array('Plain-text ', 'Here you can specify the plain text.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['personalize']     = array('Personalize ','Here you can choose whether this item should be personalized.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['addImage']        = array('Tilføj et billede', 'Hvis du vælger denne mulighed, vil et billede blive føjet til elementet.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['singleSRC']       = array('Kildefil', 'Vælg venligst en fil fra filarkivet.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['alt']             = array('Alternativ beskrivelse', 'For at gøre billeder og film tilgængelige bør du altid angive en alternativ beskrivelse med en kort beskrivelse af deres indhold.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['size']            = array('Billede bredde og højde', 'Indtast venligst enten billedbredden, billedhøjden eller begge mål for at skalere billedet. Hvis du lader begge felter stå tomme, vil den oprindelige størrelse blive brugt.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['imagemargin']     = array('Billedemargin', 'Indtast venligst margen for øverst, højre, bund og venstre og enheden. Billedmargen er mellemrummet mellem et billede og de omkringliggende elementer.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['imageUrl']        = array('Brug billede som link', 'Indtast venligst en fuldstændig URL inklusiv netværksprotokol (f.eks. <em>http://www.domain.dk</em>) for at bruge billedet som link. Bemærk at det i så fald ikke vil være muligt at se billedet i fuld størrelse længere.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['floating']        = array('Billedejustering', 'Vælg venligst billedjusteringen. Et billede kan blive vist over eller under teksten eller i øverste venstre eller øverste højre hjørne af teksten.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['caption']         = array('Billedtekst', 'Hvis du indtaster en kort tekst her, vil den blive vist under billedet. Lad dette felt være tomt for at slå denne funktion fra.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['listtype']        = array('Listetype', 'Vælg venligst listetypen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['listitems']       = array('Listepunkter', 'Indtast venligst listepunkterne. Brug knapperne til at tilføje, flytte eller fjerne et listepunkt. Hvis du arbejder uden hjælp fra JavaScript, bør du gemme ændringer inden du ændrer på rækkefølgen!');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['tableitems']      = array('Tabelindgange', 'Indtast venligst tabelindgange. Brug knapperne til at tilføje, flytte eller fjerne en tabelindgang. Hvis du arbejder uden hjælp fra JavaScript, bør du gemme ændringer inden du ændrer på rækkefølgen!');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['summary']         = array('Tabelopsummering', 'For at gøre tabeller tilgængelige bør du altid angive en kort opsummering af deres indhold.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['thead']           = array('Tabelhoved', 'Hvis du vælger denne mulighed, vil første række i tabellen blive brugt som tabelhoved.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['tfoot']           = array('Tabelfod', 'Hvis du vælger denne mulighed, vil sidste række i tabellen blive brugt som tabelfod.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['linkTitle']       = array('Link-titel', 'Link-titlen for et link vil blive vist til besøgende på dit websted i stedet for URL\'en eller kilde-filen. Hvis du laver et billed-link, vil billedet blive vist i stedet for link-titlen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['embed']           = array('Embed linket', 'Hvis du indtaster en udtryk med et wildcard <em>%s</em>, vil linket blive embeddet i udtrykket. F.eks. <em>besøg vores %s!</em> vil blive til <em>besøg vores <u>firmas websted</u>!</em>');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['multiSRC']        = array('Kildefiler', 'Vælg venligst en eller flere filer eller mapper (filer i en mappe inkluderes automatisk).');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['perRow']          = array('Miniaturebilleder pr. række', 'Angiv det antal miniaturebilleder der skal vises pr. række');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['sortBy']          = array('Sorter efter', 'Vælg venligst sorteringsrækkefølge.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['galleryHtmlTpl']  = array('HTML Galleri skabelon ','Her kan du vælge HTML galleri skabelonen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['galleryPlainTpl'] = array('Simpel Galleri skabelon ','Her kan du vælge simpel galleri skabelonen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['protected']       = array('Beskyt element', 'Vis kun indholdselementet for bestemt gruppe medlemmer.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['groups']          = array('Tilladte medlemsgrupper', 'Her kan du vælge, hvilke grupper som vil få tilladelse til at se indholdselementet.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['guests']          = array('Vis kun til gæster', 'Skjul indholdselementet, hvis et medlem logger ind.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['cssID']           = array('Stilark-ID og klasse', 'Her kan du indtaste et stilark-ID (id-attributten) og én eller flere stilark-klasser (klasse-attributten) for at kunne formatere indholdselementet vha. CSS.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['space']           = array('Mellemrum før og efter', 'Indtast venligst mellemrummet før og efter indholdselementet i pixel.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['source']          = array('Fil kilde', 'Vælg venligst den CSV fil du vil importere fra fil-mappen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['events']   		= array('Begivenheder', 'Vælg begivenhed der skal inkluderes i nyhedsbrevsteaseren.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['type_legend']      = 'Element Type';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['text_legend']      = 'Tekst';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['image_legend']     = 'Billede Indstillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['list_legend']      = 'Liste';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['table_legend']     = 'Tabel Angivelser';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['tconfig_legend']   = 'Tabel Indstillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['sortable_legend']  = 'Sorteringsmuligheder';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['link_legend']      = 'Link indstillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['imglink_legend']   = 'Billede Link Indstillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['template_legend']  = 'Skabelonindstillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['include_legend']   = 'Inkluder Indstillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['protected_legend'] = 'Adgang Beskyttelse';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['expert_legend']    = 'Ekspert Indstillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['events_legend']    = 'Begivenheder';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['anonymous']      = 'Anonym personalisering, hvis der ingen personlige data er tilgængelige';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['private']        = 'Skjul element, hvis ingen personlige data er tilgængelige';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area']['body']   = 'Indhold';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area']['header'] = 'Header';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area']['footer'] = 'Footer';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area']['left']   = 'Venstre Kolonne';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area']['right']  = 'Højre Kolonne';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['new']         = array('Nyt element ','Opret et nyt element');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['show']        = array('Emnets Detaljer ',' Nærmere oplysninger om indholdselement ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['cut']         = array('Flyt Element ',' Fly indholdselement ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['copy']        = array('Kopier Element', 'Kopier indholdselement ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['delete']      = array('Delete Element,', 'Slet indholdselement ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['edit']        = array('Rediger Element', 'Rediger indholdselement ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['pasteafter']  = array('Indsæt i Top ',' Indsæt efter artklens ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['pastenew']    = array('Opret ny artikel i Top ',' Ny artikel så der kan oprettes indhold ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['toggle']      = array('Ændre Synlighed ',' Ændre synligheden af indholdets emne ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['editalias']   = array('Kilde Rediger element ',' Rediger kildens element ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['editarticle'] = array('Rediger artikel ',' Rediger artikel ID %s');

?>