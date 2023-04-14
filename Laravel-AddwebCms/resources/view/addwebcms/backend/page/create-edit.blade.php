@extends(config('addWebCms.layout'))
@php
    $bsv = '';
    if(floatval(config('addWebCms.bootstrap_version')) >  4.6){
        $bsv = 'bs-';
    } 
@endphp
@section(config('addWebCms.display_section'))
    <div class="row justify-content-center mt-3">
        <div class="col-12 col-md-8">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-{{$bsv}}toggle="tab" data-{{$bsv}}target="#home" type="button"
                        role="tab" aria-controls="home" aria-selected="true">Meta</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-{{$bsv}}toggle="tab" data-{{$bsv}}target="#profile" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">Settings</button>
                </li>
            </ul>
            <form action="{{ route('admin.page.save-post') }}" name="componentCreateForm" id="pageCreateForm" method="POST">
                @csrf
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pageName">Name</label>
                                    <input type="text" class="form-control" id="pageName" name="pageName"
                                        placeholder="Page Name" value="{{ $data->name }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pageSlug">Slug</label>
                                    <input type="text" class="form-control" id="pageSlug" name="pageSlug"
                                        placeholder="Page Slug" value="{{ $data->slug }}" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="pageTitle">Title</label>
                                    <input type="text" class="form-control" id="pageTitle" name="pageTitle"
                                        placeholder="Page Title" value="{{ $data->title }}" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="pageCode">HTML</label>
                                    <textarea id="pageCode" class="form-control" name="pageCode" style="display: none;"></textarea>
                                    <div style="height: 200px;display: block" id="pageCodeEditor">{!! htmlentities($data->html_code) !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="form-group">
                            <label for="pageCodeSetting">Setting</label>
                            <textarea id="pageCodeSetting" class="form-control" name="pageCodeSetting" style="display: none;"></textarea>
                            <div style="height: 200px;display: block" id="pageCodeSettingEditor">{!! !empty($data->html_component_data) ? htmlentities($data->html_component_data) : '' !!}</div>
                        </div>
                    </div>
                    <input type="hidden" name="pageId" id="pageId" value="{{ $data->id }}">
                </div>
                <div class="mt-2 mb-4">
                    <button type="button" class="btn btn-primary" onclick="beautifyCode()">Butify Code</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a role="submit" class="btn btn-primary" href="{{ route('admin.page.list') }}">Back</a>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="card" style="margin-top: 2.6rem;">
                <div class="card-body">
                    <div class="form-group">
                        <label for="pageTitle">Compenents</label>
                        <select name="" id="components" class="form-control">
                            <option value="">Select Component</option>
                            @foreach ($components as $component)
                            <option value="{{$component->id}}">{{$component->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary" id="addComponent">Add Component</button>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <label for="">HTML Components</label>
                    <div>
                        <div class="alert alert-sm alert-primary alert-dismissible fade show py-2" role="alert">
                            Alert
                            <button type="button" class="close py-2 px-3" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/vendor/addwebcms/src-noconflict/ace.js') }}"></script>
    <script src="{{ asset('/vendor/addwebcms/src-noconflict/ext-beautify.js') }}"></script>
    <script type="module">
        import { jsonrepair } from "https://jspm.dev/jsonrepair";
        window.fixIt = (jsonString) => {
            try {
                return jsonrepair(jsonString)
            } catch (err) {
                try {
                    return jsonString;
                } catch (err) {
                    console.log("validate json");
                }
            }
        }
    </script>
    <script>
        let pageCodeEditor = ace.edit("pageCodeEditor");
        let pageCodeSettingEditor = ace.edit("pageCodeSettingEditor");
        let beautify = ace.require("ace/ext/beautify");

        pageCodeEditor.setTheme("ace/theme/chrome");
        pageCodeEditor.session.setMode("ace/mode/php_laravel_blade");

        pageCodeSettingEditor.setTheme("ace/theme/chrome");
        pageCodeSettingEditor.session.setMode("ace/mode/json");

        clearWhiteSpace = function(data) {
            console.log(data.replace(/\n[ ]*!/g, ""));
            return data.replace(/\n[ ]*!/g, "");
        }

        beautifyCode = function() {
            beautify.beautify(pageCodeEditor.session);
            beautify.beautify(pageCodeSettingEditor.session);
        }

        let loginForm = document.getElementById("pageCreateForm");
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            document.getElementById('pageCode').value = clearWhiteSpace(pageCodeEditor.session.getValue());
            document.getElementById('pageCodeSetting').value = clearWhiteSpace(pageCodeSettingEditor.session.getValue());

            console.log(document.getElementById('pageCode').value, document.getElementById('pageCodeSetting').value);
            loginForm.submit();
        });
        function jsonGenerator(component) {
            let settingJson = `"${component.slug}":{`;
            var regex = /@verbatim{{\s*\$(\w+)\s*}}@endverbatim/g;
            var variableNames = [];
            var match;
            while (match = regex.exec(component.html_component)) {
                settingJson += `"${match[1]}" : "Enter Value"`;
                variableNames.push(match[1]);
            }
            settingJson += "}";
            return settingJson;
        }
        document.querySelector('#addComponent').addEventListener('click', (e)=>{
            let component = document.querySelector('#components').value;
            fetch("{{route('admin.component.edit-get')}}/"+component, {
                method: "get",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            })
            .then(res => res.json())
            .then(data => {
                let component = data.componentModel;
                let settingJson = '{';

                let SetData = '';
                let SetSettingData = pageCodeSettingEditor.getValue();
                if(pageCodeEditor.getValue() != ''){
                    SetData += pageCodeEditor.getValue() + '\n';
                }
                SetData += component.html_component;
                pageCodeEditor.setValue(SetData);
                beautify.beautify(pageCodeEditor.session);
                // working on json string appending STOP
                if(SetSettingData != ''){
                    SetSettingData.substring(0,SetSettingData.length - 1) + ",";
                }
                settingJson += SetSettingData + jsonGenerator(component) + "}";
                SetSettingData = window.fixIt(settingJson);
                pageCodeSettingEditor.setValue(SetSettingData);
                beautify.beautify(pageCodeSettingEditor.session);
            })
            .catch(error => {
                console.log(error);
            });
        });
    </script>
@endsection
