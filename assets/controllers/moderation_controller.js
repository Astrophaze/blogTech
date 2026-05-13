import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['bouton'];
    static values = { url: String, approved: Boolean };

    approvedValueChanged() {
        this.boutonTarget.textContent = this.approvedValue ? 'Désapprouver' : 'Approuver';
        this.boutonTarget.className = this.approvedValue
            ? 'btn btn-warning'
            : 'btn btn-success';
    }

    async toggle() {
        this.boutonTarget.disabled = true;

        const response = await fetch(this.urlValue, {
            method: 'PATCH',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`Erreur ${response.status}`);
        }

        const data = await response.json();
        console.log(data);

        this.approvedValue = data.approved;
        this.boutonTarget.disabled = false;
    }
}
