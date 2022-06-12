<?php
$url = get_home_url();
$post_date = $res;
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<script>
			jQuery(function ($) {
				var baseUrl = '<?php echo $url;?>';
				//var arrowImgUrl = $('#arrow-img-url').data('arrowImgUrl')

				//全部で何件あるか
				var totalCount = 0

				//何件ずつ表示するか
				var perPage = 10

				//現在のページ
				var nowPage = 1

				//投稿を取得する
				getPost()

				//もっと見るボタンをクリックしたとき
				$('.view-more').on('click', function() {
					nowPage++
					getPost()
				})

				function getPost() {
					$.ajax({
						url: baseUrl + '/wp-json/wp/v2/posts/?_embed',
						type: 'GET',
						data: {
							'page': nowPage,
							'per_page': perPage,
						}
					})
					.done(function(data, status, xhr) {
						//トータルの記事数を取得する
						totalCount = xhr.getResponseHeader('X-WP-Total')
						//現在時刻
						var now = new Date(Date.now());
						for(var i = 0; i < data.length; i++) {
							var target = data[i];

							//投稿情報取得
							var id = target.id;
							var link = target.link;
							var title =  target.title.rendered;
							media_id = 0;
							media_url = '';
							media_id = data[i].featured_media;				
							//日時比較
							var startdate = target.post_meta.sdate;
							var closedate = target.post_meta.edate;
							var sd = startdate.replace(/-/g,',');
							var cd = closedate.replace(/-/g,',');
							var openday = new Date(sd);
							var closeday = new Date(cd);
							//公開日に入力があるか
							//入力なし=0、あり=1
							if (!startdate.length){
								var startflag = 0;
							}else{
								var startflag = 1;
							}
							//終了日に入力があるか
							//入力なし=0、あり=1
							if (!closedate.length){
								var closeflag = 0;
							}else{
								var closeflag = 1;
							}
							//本日が公開日を超えているか
							//超えている=0、超えていない=1
							if(now>openday) {
								var startcheck = 0;
							}else{
								var startcheck = 1;
							}
							//本日が終了日を超えているか
							//超えていない=0、超えている=1
							if(now<closeday) {
								var closecheck = 0;
							}else{
								var closecheck = 1;
							}
							//公開対象か
							if( startflag == 0 && closeflag == 0){
								publish = 0;
							}else if( startflag == 1 && closeflag == 0){
								publish = 0;
							}else if(startcheck == 0 && closecheck == 0){
								publish = 0;
							}else if(startcheck == 1 && closecheck == 0){
								publish = 1;
							}else if(startcheck == 1 && closecheck == 1){
								publish = 1;
							}else{
								publish = 1;
							}

							//出力するHTML
							html = '<li>'
							$( data[i]._embedded['wp:featuredmedia'] ).each( function( index, element ) {
								if( element.id != media_id ) return true;
								media_url = element.source_url;
								var thumb = media_url;
								html += '<img src="' + thumb + '">'
								return false;
							} );
							html += '<a href="' + link + '" id="'+ id +'">'
							if(publish == 0){
								html += '<p>【公開記事】</p>'
							}else{
								html += '<p>【非公開記事】</p>'					
							}
							html += '<p>' + title + '</p>'
							html += '</a>'
							//html += '<div>'+ sdate +'</div>'
							html += '</li>'
							//出力する親要素
							$('#news-list').append(html)

						}

						//すべて表示している場合は「もっと見るボタン」は表示しない
						if(totalCount < perPage * nowPage) {
							$('.view-more').hide()
						}
						console.log(totalCount);
					})
			//        .fail(function(data) {
			//            console.log(data)
			//        })
				}
			})
			</script>

			<!--------------------------------------------------------------------------------------------------------------->
			<ul id="news-list" class="news-list">
				<!-- この中に記事の内容が描画されます -->
			</ul>

			<button class="view-more normal-button">もっと見る</button>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
