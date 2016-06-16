
        <h2 class="title">{$title}</h2>

        <form id="form" class="base mrbt10" action="?m=announcement&a=an_save" method="post" >
                <div class="toolbar">
                        <div class="block">
                                <label class="">标题：</label>
                                <input autocomplete="off"   maxlength="32" class="" name="an_title" type="text" style="width:530px" required="true"/>
                        </div>

                        <br />
                        <div class="block">
                                <label class="">区域：</label>
                                <select multiple="TRUE" value="" class="autofix autoedit" name="an_area[]"  action="?m=area&a=option" selected="true">
                                        {'<option value="#">全部</option>'|isallarea}
                                </select>
                        </div>
                        <p class="info_text">区域部分单击单选，按住ctrl同时多选，按住shift连续选择多项</p>
                </div>
                <script charset="utf-8" src="kindeditor/kindeditor-min.js"></script>
		<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>  
                <script>
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('textarea[name="content"]', {
					resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'source', '|', 'undo', 'redo', '|', 'preview', 'template', 'code', 'cut', 'copy', 'paste',
		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
		'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|',
		'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
		'anchor', 'link', 'unlink']
				});
			});
		</script>
                <div class="content">
                        <textarea maxlength="3000" name="content" class="ckeditor" id="content" style="width: 624px;height: 400px"></textarea>
                </div>

                <div class="buttons mrtop40">
                        <a goto='?m=announcement&a=index'  form="form" class="ajaxpost2 button normal" >发布</a>
                        <a goto='?m=announcement&a=index' form="form" class="ajaxpost1 button normal">保存为草稿</a>
                        <a class="goback button">取消</a>
                </div>
        </form>