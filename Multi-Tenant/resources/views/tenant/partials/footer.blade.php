 <!-- partial:../../partials/_footer.html -->
 <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">LostAndFound</span>
        <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">{{ file_exists(base_path('version.txt')) ? trim(file_get_contents(base_path('version.txt'))) : 'v0.0.0' }}</span>
    </div>
</footer>

