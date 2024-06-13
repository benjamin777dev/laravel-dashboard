<footer class="footer position-sticky" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>document.write(new Date().getFullYear())</script> © zPortal.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Colorado Home Realty, 2024
                </div>
            </div>
        </div>
    </div>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var button = document.querySelector('.navbar-toggler');
        var sidebar = document.querySelector('.vertical-menu');

        button.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        const modalSelectMap = [{
            modalID: 'global-search',
            selectElementId: 'global-search'
        }, ];

        modalSelectMap.forEach(({
            modalID,
            selectElementId
        }) => {
            const selectElement = $(`#${selectElementId}`);
            showDropdown(modalID, selectElement);
        });
    });
</script>
