import * as jQuery from "jquery";
require('../../vendor/tetranz/select2entity-bundle/Resources/public/js/select2entity');
require('select2');
import {CollectionHelper} from '../../vendor/unlooped/ts-resources/src/helper/CollectionHelper';
require('../../vendor/unlooped/grid-bundle/Resources/assets/ts/main');

jQuery('*[data-collection="form-collection"]').each((index, el) => {
    new CollectionHelper(el);
    let container = jQuery(el);
    container.on('unl.row_added', () => {
        container.find('.initSelect2').select2();
    });
});

jQuery('.initSelect2').select2();
