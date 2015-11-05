/* ========================================================================
 * Employee App */

function App() {
    /**
     * Confirm delete recode
     * @returns {Boolean}
     */
    this.confirmActionDelete = function(item) {
        
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