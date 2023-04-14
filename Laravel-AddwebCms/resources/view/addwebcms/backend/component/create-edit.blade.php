@extends(config('addWebCms.layout'))

@section(config('addWebCms.display_section'))
    <div class="container mt-3">
        <form name="componentCreateForm" id="componentCreateForm" action="{{ route('admin.component.save-post') }}"
            method="POST">
            @csrf
            <div class="form-group">
                <label for="componentName">Name</label>
                <input type="text" class="form-control" id="componentName" name="componentName" placeholder="Component Name"
                    value="{{ $data->name }}" />
            </div>
            <div class="form-group">
                <label for="componentSlug">Slug</label>
                <input type="text" class="form-control" id="componentSlug" name="componentSlug"
                    placeholder="Component Slug" value="{{ $data->slug }}" />
            </div>
            <div class="form-group">
                <label for="componentCode">HTML</label>
                <textarea id="componentCode" class="form-control" name="componentCode" style="display: none;"></textarea>
                <div style="height: 200px;display: block" id="componentCodeEditor">{!! htmlentities($data->html_component) !!}</div>
            </div>
            <input type="hidden" name="componentId" id="componentId" value="{{ $data->id }}">
            <button type="button" class="btn btn-primary" onclick="beautifyCode()">Butify Code</button>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a role="submit" class="btn btn-primary" href="{{ route('admin.component.list') }}">Back</a>
        </form>
    </div>
    <script src="{{ asset('/vendor/addwebcms/src-noconflict/ace.js') }}"></script>
    <script src="{{ asset('/vendor/addwebcms/src-noconflict/ext-beautify.js') }}"></script>
    <script>
        let editor = ace.edit("componentCodeEditor");
        let beautify = ace.require("ace/ext/beautify");

        editor.setTheme("ace/theme/chrome");
        editor.session.setMode("ace/mode/php_laravel_blade");

        clearWhiteSpace = function(data) {
            console.log(data.replace(/\n[ ]*!/g, ""));
            return data.replace(/\n[ ]*!/g, "");
        }

        beautifyCode = function() {
            beautify.beautify(editor.session);
        }

        let loginForm = document.getElementById("componentCreateForm");
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            document.getElementById('componentCode').value = clearWhiteSpace(editor.session.getValue());

            loginForm.submit();
        });
    </script>
@endsection
