<div id="customConfirmModal" class="hidden fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 shadow-2xl w-full max-w-sm text-center">
        <p id="customConfirmText" class="text-slate-800 text-lg font-semibold mb-6"></p>
        <div class="flex justify-center gap-4">
            <button type="button" onclick="document.getElementById('customConfirmModal').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-slate-200 text-slate-800 font-medium hover:bg-slate-300">
                Annuler
            </button>
            <button type="button" id="customConfirmOk" class="px-4 py-2 rounded-lg text-white font-medium shadow-lg">
                Confirmer
            </button>
        </div>
    </div>
</div>

<script>
    window.showCustomConfirm = function(event, message, actionType = 'default') {
        event.preventDefault();
        
        const modal = document.getElementById('customConfirmModal');
        const btn = document.getElementById('customConfirmOk');
        
        // Capturer l'élément cible maintenant car event.currentTarget deviendra null plus tard
        const targetElement = event.currentTarget.closest('form') || event.currentTarget;
        
        // Mettre le texte
        document.getElementById('customConfirmText').innerText = message;
        
        // Changer la couleur
        btn.className = 'px-4 py-2 rounded-lg text-white font-medium shadow-lg ' + 
            (actionType === 'statut' ? 'bg-amber-600 hover:bg-amber-700' : 
            (actionType === 'default' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-rose-600 hover:bg-rose-700'));

        // Valider l'action
        btn.onclick = function() {
            modal.classList.add('hidden');
            if (targetElement.tagName === 'FORM') {
                targetElement.submit();
            } else {
                window.location.href = targetElement.href;
            }
        };

        // Afficher la modale
        modal.classList.remove('hidden');
    };
</script>
