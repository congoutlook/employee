/* ========================================================================
 * Employee App */

function App() {
    
    /**
     * Preview the image after user choose file
     * @param object element
     * @param string | object container
     * @returns void
     */
    this.previewImageUpload = function(element, container) {
        
        if (element.files && element.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(container).attr('src', e.target.result);
            }

            reader.readAsDataURL(element.files[0]);
        }
    }
    
    /**
     * Confirm delete recode
     * @returns {Boolean}
     */
    this.confirmActionDelete = function (item) {

        var msg = '';
        if (typeof item !== 'undefined') {
            msg = 'You want to delete "' + item + '". Are you sure?';
        } else {
            msg = 'You want to delete this recode. Are you sure?';
        }

        if (!confirm(msg)) {
            return false;
        }
        return true;
    }
}

var app = new App();