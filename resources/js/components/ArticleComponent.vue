<template>
    <div class="container p-5">
    
        <nav aria-label="Page navigation example">
    
            <ul class="pagination justify-content-center">
    
                <li class="page-item" :class="{disabled:!page.link.prev}"><a class="page-link" @click="fetchArticles(page.link.prev)" href="#">Prev</a></li>
    
                <li class="page-item disabled"><a class="page-link " href="#">Page {{ page.meta.current_page }} of {{ page.meta.last_page }}</a></li>
    
                <li class="page-item" :class="{disabled:!page.link.next}"><a class="page-link" @click="fetchArticles(page.link.next)" href="#">Next</a></li>
    
            </ul>
    
            <div>
    
                <p class="text-center">Article: {{ page.meta.from }} to {{ page.meta.to }}</p>
    
            </div>
    
        </nav>
    
        <div class="row justify-content-center">
            <div class="col-12">
    
    
                <form class="">
                    <div class="form-group">
                        <input type="text" class="form-control" v-model="article.title" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" v-model="article.author" placeholder="Author">
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control" v-model="article.body" placeholder="Body"></textarea>
                    </div>
                    <button class="btn btn-primary w-100 mb-3" @click="addArticle(article)">Create article</button>
                </form>
            </div>
            <div class="col-12">
    
                <div class="card shadow card-default mb-5" v-for="(article,index) in articles" :key="index">
   
                    <div  class="card-body">
    
                        <h3 class="card-title"><a href="#" data-toggle="modal" @click="currentArticle(article)" data-target="#exampleModalLong">{{ article.title }}</a></h3>
    
                        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">{{ current_article.title }}</h5>
    
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                                    </div>
                                    <p>Author: {{ current_article.author }}</p>
                                    <p>Created at: {{ current_article.created_at }}</p>
                                    <div class="modal-body">
                                        {{ current_article.body }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <p class="card-subtitle mb-1 text-muted">Author: <b>{{ article.author }}</b></p>
    
                        <p class="card-subtitle text-muted">Posted: {{ convertDateToYear(article.created_at) }}</p>
    
                        <p class="card-text"> {{ article.body }}</p>
    
                    </div>
                    
                    <div class="p-2">
                        <button class="btn btn-success w-100 mb-1" @click="editArticle(article)">Edit</button>
    
                    </div>
                    <div class="p-2">
                        <button class="btn w-100 mb-3 btn-danger" @click="removeArticle(article)">Delete</button>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="sass" scoped>

</style>

<script>
    export default {
        data() {
            return {
    
                articles: '',
                article: {
                    id: '',
                    author: '',
                    title: '',
                    body: '',
                },
                current_article: {
                    id: '',
                    author: '',
                    title: '',
                    body: '',
                    created_at: ''
                },
                page: {
                    link: {},
                    meta: {}
                },
                edit: false
            }
        },
        created() {
    
            this.fetchArticles();
    
        },
        methods: {
            fetchArticles(articlePage) {
                let vm = this;
                var page = articlePage || 'api/articles';
    
                axios.get(page)
                    .then((response) => {
    
                        vm.articles = response.data.data;
                        let linkInfo = response.data.links;
                        let metaInfo = response.data.meta;
    
                        vm.getPages(linkInfo, metaInfo);
    
                    }).catch((error) => {
                        alert(error);
                    });
            },
            getPages(link, meta) {
                let links = {
                    first: link.first,
                    next: link.next,
                    prev: link.prev,
                    last: link.last
                };
    
                let metas = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    from: meta.from,
                    to: meta.to
                };
    
                this.page.meta = metas;
                this.page.link = links;
            },
    
            convertDateToYear(date) {
    
                let newdate = new Date(date);
    
                return newdate.toLocaleTimeString();
    
    
            },
            emptyArticle(article) {
                article = {
                    id: '',
                    author: '',
                    title: '',
                    body: '',
                };
            },
            currentArticle(article) {
                this.current_article.id = article.id;
                this.current_article.title = article.title;
                this.current_article.author = article.author;
                this.current_article.body = article.body;
                this.current_article.created_at = article.created_at;
            },
            addArticle(article) {
    
                if (this.edit === false) {
                    axios({
                        method: 'post',
                        url: '/api/articles',
                        data: {
                            title: article.title,
                            author: article.author,
                            body: article.body
                        }
                    }).then(() => {
                        alert('Article posted!');
                    });
    
                    this.fetchArticles();
                } else if (this.edit === true) {
                    axios({
                        method: 'put',
                        url: '/api/articles/' + article.id,
                        data: {
                            id: article.id,
                            title: article.title,
                            author: article.author,
                            body: article.body
                        }
                    }).catch((error) => {
                        console.log(error);
                    });
                    
                    emptyArticle(article);
                    this.edit = false;
    
                    this.fetchArticles();
                }
    
    
            },
            removeArticle(article) {
                axios({
                    method: 'delete',
                    url: "api/articles/" + article.id,
    
                }).then(() => {
                    alert('article deleted!');
    
                });
                this.fetchArticles();
            },
            editArticle(article) {
                this.edit = true;
                this.article.id = article.id;
                this.article.title = article.title;
                this.article.author = article.author;
                this.article.body = article.body;
            }
    
    
        }
    }
</script>

<style lang="scss" scoped>
    .card {
        transition: .5s;
    }
    
    .card:hover {
        transform: scale(1.05);
    }
</style>
