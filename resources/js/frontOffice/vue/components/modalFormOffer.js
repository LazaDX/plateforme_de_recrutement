import { reactive } from 'vue';

export default {
    data() {
        return {
            showModal: false,
            offreId: this.offre.id,
            questions: this.offre.questionFormulaire,
            regions: this.regions,
            districts: reactive({}),
            communes: reactive({}),
            responses: reactive({}),
            isSubmitting: false,
            prologue: 'Envoyer'
        };
    },
    created() {
        this.questions.forEach(question => {
            this.responses[question.id] = {
                valeur: '',
                region_id: question.region_id || '',
                district_id: question.district_id || '',
                commune_id: question.commune_id || ''
            };
            this.districts[question.id] = [];
            this.communes[question.id] = [];
        });
    },
    methods: {
        async fetchDistricts(questionId) {
            const regionId = this.responses[questionId].region_id;
            this.responses[questionId].district_id = '';
            this.responses[questionId].commune_id = '';
            this.districts[questionId] = [];
            this.communes[questionId] = [];

            if (regionId) {
                try {
                    const response = await fetch(this.districtsRoute.replace(':region', regionId), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    this.districts[questionId] = await response.json();
                } catch (error) {
                    console.error('Erreur chargement districts:', error);
                    this.$notyf.error('Impossible de charger les districts');
                }
            }
        },
        async fetchCommunes(questionId) {
            const districtId = this.responses[questionId].district_id;
            this.responses[questionId].commune_id = '';
            this.communes[questionId] = [];

            if (districtId) {
                try {
                    const response = await fetch(this.communesRoute.replace(':district', districtId), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    this.communes[questionId] = await response.json();
                } catch (error) {
                    console.error('Erreur chargement communes:', error);
                    this.$notyf.error('Impossible de charger les communes');
                }
            }
        },
        async submitForm() {
            this.isSubmitting = true;
            const formData = new FormData(this.$refs.applicationForm);
            formData.append('offre_id', this.offreId);

            Object.keys(this.responses).forEach(questionId => {
                formData.set(`reponses[${questionId}][valeur]`, this.responses[questionId].valeur || '');
                if (this.responses[questionId].region_id) {
                    formData.set(`reponses[${questionId}][region_id]`, this.responses[questionId].region_id);
                }
                if (this.responses[questionId].district_id) {
                    formData.set(`reponses[${questionId}][district_id]`, this.responses[questionId].district_id);
                }
                if (this.responses[questionId].commune_id) {
                    formData.set(`reponses[${questionId}][commune_id]`, this.responses[questionId].commune_id);
                }
            });

            try {
                const response = await fetch(this.submitRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (!response.ok) {
                    console.error('Error response:', data);
                    if (data.errors) {
                        Object.values(data.errors).forEach(err => {
                            this.$notyf.error(err[0]);
                        });
                    } else {
                        this.$notyf.error(data.message || 'Une erreur s\'est produite. Vérifiez vos champs.');
                    }
                    return;
                }

                this.showModal = false;
                this.$notyf.success(data.message || 'Votre candidature a été envoyée avec succès !');
                setTimeout(() => {
                    window.location.href = this.submitRoute;
                }, 1000);
            } catch (error) {
                console.error('Submission error:', error);
                this.$notyf.error('Erreur inattendue. Veuillez réessayer ultérieurement.');
            } finally {
                this.isSubmitting = false;
            }
        }
    },
    mounted() {
        this.$notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top'
            },
            types: [{
                type: 'info',
                background: 'blue'
            }]
        });

        this.questions.forEach(question => {
            if (question.type === 'geographique' && question.region_id && question.all_districts && !question.district_id) {
                this.responses[question.id].region_id = question.region_id;
                this.fetchDistricts(question.id);
            }
            if (question.type === 'geographique' && question.district_id && question.all_communes && !question.commune_id) {
                this.responses[question.id].district_id = question.district_id;
                this.fetchCommunes(question.id);
            }
        });
    }
};
