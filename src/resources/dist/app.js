/**
 * Prefix Entries plugin for Craft CMS
 *
 * @author    Bryant Wells
 * @copyright Copyright (c) 2020 Bryant Wells
 * @link      github.com/bryantwells
 * @package   PrefixEntries
 * @since     1.0.0PrefixEntries
 */

function prefixEntries(settings) {
    settings.fieldTypes.forEach((fieldType, i) => {
        // create selector from settings passed by the plugin
        const selector = `.field[data-type="${fieldType}"]`;

        // get all instances of the plugin's field present on the page
        [...document.querySelectorAll(selector)].forEach((fieldElement, j) => {
            new PrefixEntriesField(fieldElement, i+j, settings)
        });
    });
}

class PrefixEntriesField {
    constructor(element, id, settings) {
        this.element = element;
        this.id = id;
        this.settings = settings;
        this.mutationEvent = new Event(`PrefixEntries-${ this.id }-mutation`);
        this.observer = new MutationObserver(mutationsList => {
                this.observeSelectedEntries(mutationsList)
            });
        
        this.init();
    }

    init() {
        // observe parent element for changes in selected entries list
        this.observer.observe(
            this.element.querySelector(`.elements`), 
            { childList: true });

        // prefix entries on load
        this.editEntryElements();

        // prefix entries on mutation
        document.addEventListener(`PrefixEntries-${ this.id }-mutation`, () => {
            this.editEntryElements();
        });
    }

    editEntryElements() {
        // get each entry element
        [...this.element.querySelectorAll('.element')].forEach((entryElement) => {
            this.editEntryElement(entryElement);
        });
    }

    editEntryElement(entryElement) {
        // edit the entry element
        const entryObject = this.settings.entries.find(entry => entry.id == entryElement.dataset.id);
        const newEntryTitle = this.prefixEntryTitle(entryObject.title, entryObject);
        entryElement.querySelector('.title').innerText = newEntryTitle;
    }

    prefixEntryTitle(title, entryObject) {
        // (recursive) prepend the entries parent(s) to the title
        if (entryObject.parentId) {
            const parentObject = this.settings.entries.find(entry => entry.id === entryObject.parentId)
            return this.prefixEntryTitle(`${parentObject.title} > ${title}`, parentObject);
        } else {
            return title;
        }
    }

    observeSelectedEntries(mutationsList) {
        // dispatch mutation event
        document.dispatchEvent(this.mutationEvent);
    }
}