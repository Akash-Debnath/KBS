<template>

    <div v-if="isLoading">
        <Loader></Loader>
    </div>
    <div v-else v-cloak class="py-30 py-md-60 min-height-wrapper">
        <div class="container">
            <div v-if="isFinish">
                <h3 class="text-center font-weight-bold">Your Score: {{ showResult.toFixed(2) }}</h3>

                <div class="pt-3 pb-3" v-if="!(showResult>=passMark)">
                    <h3 class="pb-5">Please read below articles to improve your knowledge!</h3>
                    <ul class="text-left">
                        <li class="text-left" v-for="a_article in article_list" :key="a_article">
                            <a href="#" @click.prevent="articleDetails(a_article)">{{a_article.split('/')[1]}}</a>
                        </li>
                    </ul>
                </div>

                <div class="text-center" v-if="!userInformation && showResult>=passMark">
                    <router-link  class="" :to="{ name: 'Registration', query: { quizId:quizInfo.id, score:showResult }}">
                        Register Now
                    </router-link>
                </div>



            </div>
            <div v-else>
                <div v-if="quizInfo">
                    <div class="exam-wrapper border rounded-lg mb-30">
                        <div class="row">
                            <div class="col-md-4 order-md-2">
                                <div class="mt-wrapper p-15 h-100">
                                    <div class="time-left pt-0 pt-md-2"><strong>Time Left: </strong>{{timeCounter}}</div>
                                    <div class="answer-mark"><strong>Marks: </strong>{{ markCounter.toFixed(2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-8 order-md-1 mb-15">
                                <div class="qa-wrapper p-15" v-for="a_form_field in quizFormFieldInfo" :key="a_form_field.id">
                                    <h3 class="my-0 pb-10 question-title">{{a_form_field.f_label}}</h3>
                                    <div v-if="a_form_field.f_type === 'Text'">
                                        <input class="form-control" :max="a_form_field.f_max_value" :type="a_form_field.f_type" :name="a_form_field.f_name" :class="a_form_field.f_class" :id="a_form_field.f_id" :required="a_form_field.f_required==0? false: true" v-model="fromData"  v-on:keyup.enter="checkNextData(),getQuizFormField(pagination.next_page_url)"/>
                                    </div>

                                    <div v-if="a_form_field.f_type === 'Email'">
                                        <input class="form-control" :max="a_form_field.f_max_value" :type="a_form_field.f_type" :name="a_form_field.f_name" :class="a_form_field.f_class" :id="a_form_field.f_id" :required="a_form_field.f_required==0? false: true" v-model="fromData"  v-on:keyup.enter="checkNextData(),getQuizFormField(pagination.next_page_url)"/>
                                    </div>

                                    <div v-if="a_form_field.f_type === 'Select/Dropdown'">
                                        <select class="form-control" :name="a_form_field.f_name" :class="a_form_field.f_class" :id="a_form_field.f_id" :required="a_form_field.f_required==0? false: true" v-model="fromData" v-on:keyup.enter="checkNextData(),getQuizFormField(pagination.next_page_url)">
                                            <option value="" disabled>Select one</option>
                                            <option v-for="(a_val) in selectBoxOption" :key="a_val" :value="a_val">{{a_val}}</option>
                                        </select>
                                    </div>

                                    <div v-if="a_form_field.f_type === 'Radio'">
                                        <div v-for="(a_val,index) in selectBoxOption" :key="a_val">
                                            <input :type="a_form_field.f_type" :name="a_form_field.f_name" :class="a_form_field.f_class" :id="a_form_field.f_id+'_'+index" :required="a_form_field.f_required==0? false: true" v-model="fromData" @click="onChange(a_val)"/>
                                            <label class="ml-2" :for="a_form_field.f_id+'_'+index">{{ a_val }}</label><br>
                                        </div>
                                    </div>

                                    <div v-if="a_form_field.f_type === 'Number'">
                                        <input class="form-control" :max="a_form_field.f_max_value" :type="a_form_field.f_type" :name="a_form_field.f_name" :class="a_form_field.f_class" :id="a_form_field.f_id" :required="a_form_field.f_required==0? false: true" v-model="fromData"  v-on:keyup.enter="checkNextData(),getQuizFormField(pagination.next_page_url)"/>
                                    </div>

                                    <div v-if="a_form_field.f_type === 'Textarea'">
                                        <textarea class="form-control" rows="5" :max="a_form_field.f_max_value" :name="a_form_field.f_name" :class="a_form_field.f_class" :id="a_form_field.f_id" :required="a_form_field.f_required==0? false: true" v-model="fromData" v-on:keyup.enter="checkTextAreaData(),checkNextData(),getQuizFormField(pagination.next_page_url)"></textarea>
                                    </div>

                                    <div v-if="a_form_field.f_type === 'Password'">
                                        <input class="form-control" :max="a_form_field.f_max_value" :type="a_form_field.f_type" :name="a_form_field.f_name" :class="a_form_field.f_class" :id="a_form_field.f_id" :required="a_form_field.f_required==0? false: true" v-model="fromData"  v-on:keyup.enter="checkNextData(),getQuizFormField(pagination.next_page_url)"/>
                                    </div>

                                    <div v-if="a_form_field.f_type === 'Checkbox'">
                                        <div v-for="(a_val,index) in selectBoxOption" :key="a_val">
                                            <input :type="a_form_field.f_type" :name="a_form_field.f_name" :class="a_form_field.f_class" :id="a_form_field.f_id+'_'+index" :required="a_form_field.f_required==0? false: true" v-model="fromData" @click="onChangeCheck(a_val)"/>
                                            <label class="ml-2" :for="a_form_field.f_id+'_'+index">{{ a_val }}</label><br>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-common btn-primary px-25 text-white font-16 mt-15" :class="[(itemA == 0) ? 'd-none':'show btn-info']" @click="checkNextData(a_form_field.id), finalizeData(), saveResult()">Finish Exam</button>
                                    </div>
                                </div>
                                <div class="mt-0 px-15">
                                    <div v-for="a_form_field in quizFormFieldInfo" :key="a_form_field.id">
                                        <div>
                                            <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4">
                                                <ul class="pagination">
                                                    <li :class="[{disabled:!pagination.next_page_url},(itemA == 1) ? 'd-none':'']" class="page-item mx-1">
                                                        <a @click.prevent="checkNextData(a_form_field.id),getQuizFormField(pagination.next_page_url)" href="#" class="btn btn-common btn-primary px-25 text-white font-18"><span>Next</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    go back to quiz page
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import axios from 'axios'
    import Loader from "../components/Loader";
    import $ from 'jquery'
    export default {
        name: "StartExam",

        components:{
            Loader,
        },

        data(){
            return{
                questionArray : [],
                isLoading: true,
                isFinish: false,
                isRepeat: true,
                quizInfo: '',
                userId  :'',
                quizFormFieldInfo:'',
                selectBoxOption:'',
                userInformation:'',
                itemA : 0,
                fromData: '',
                allFromData:[],
                quesAndAns: [],
                timeCounter: '',
                clearCounter:'',
                markCounter: '',
                resultArray: [],
                showResult:'',
                passMark:'',
                checkBoxDataArr : [],
                strToArr : '',

                pagination:{
                    from: '',
                    to: '',
                    current_page: 1,
                    first_page_url: '',
                    last_page: '',
                    last_page_url: '',
                    next_page_url:'',
                    prev_page_url: '',
                    path: '',
                    per_page: 1,
                    total: ''
                },

                article_list : ''
            }
        },

        methods:{
            articleDetails(url){
                this.$router.push('/article-detail/'+url);
            },
            checkTextAreaData(){
                this.fromData = this.fromData.replace("\n", "");

            },
            onChange(val){
                let _that = this;
                _that.fromData = val;
            },
            onChangeCheck(val) {
                let _that = this;
                _that.checkBoxDataArr.push(val)
                // console.log(_that.checkBoxDataArr);
                // console.log(_that.strToArr);
                _that.fromData = _that.checkBoxDataArr.join();
            },
            checkNextData(quesId) {
                let _that = this;
                _that.allFromData.push(_that.fromData);
                _that.quesAndAns[quesId] = _that.fromData;
                //console.log(_that.quizInfo.id);
                _that.checkBoxDataArr = [];
                _that.strToArr = [];
                if (_that.isRepeat == false ){
                    _that.finalizeData();
                }
            },
            finalizeData(){
                let _that = this;

                _that.checkBoxDataArr = [];
                _that.isFinish = true;

                clearInterval(_that.clearCounter);
                if (_that.isRepeat==false){
                    console.log(_that.resultArray);
                    console.log(_that.allFromData);
                    let n = 0;
                    let score=0;
                    for (n; n<_that.resultArray.length; n++) {
                        if (_that.resultArray[n].toLowerCase() == _that.allFromData[n].toLowerCase()) {
                            score+=_that.markCounter;
                        }
                    }
                    _that.showResult = score;
                    _that.questionArray = [];
                    // console.log(_that.questionArray);
                }
            },

            saveResult() {

                let _that = this;
                if(_that.userInformation) {
                    _that.userId= _that.userInformation.id;
                } else{
                    _that.userId ='';
                }
                let ques_and_ans = JSON.stringify({..._that.quesAndAns});
                // console.log('ques and answer',_that.quesAndAns);
                // console.log('ques and answer',ques_and_ans);
               


                let postData = new FormData();
                postData.append('user_id', _that.userId);
                postData.append('quiz_id', _that.quizInfo.id);
                postData.append('ques_and_ans', ques_and_ans);
                postData.append('score', _that.showResult);
                postData.append('status', _that.showResult>=_that.passMark?1:0);

                axios.post('result-create',postData).then(function (response){
                    // console.log(response.data);
                    if (response.data.status_code ==200){
                        console.log("Quiz result successfully added")

                    } else {
                        console.log(response.data.messages);
                    }
                })
            },

            getQuizFormField(pageUrl) {
                let _that = this;
                if (_that.quizInfo.number_of_questions == 1){
                    // console.log("hi");
                    pageUrl = pageUrl == undefined ? '/quiz-form/field-list/'+_that.quizInfo.quiz_form_id+'?page=1' : pageUrl;
                    axios.get(pageUrl)
                        .then(function (response) {
                            _that.isLoading= false;
                            _that.quizFormFieldInfo = response.data.quiz_form_field_list.data;
                            _that.pagination  = response.data.quiz_form_field_list;


                            _that.quizFormFieldInfo.forEach(val =>{
                                _that.resultArray.push(val.f_default_value);
                                if (val.f_option_value !=null && val.f_option_value.search(',')){
                                    let str = val.f_option_value;
                                    str = str.split(",");
                                    // _that.strToArr = Object.values(str);

                                    // console.log("before"+ str);
                                    str = str.sort(() => Math.random() - 0.5);

                                    // console.log("after" + str);

                                    _that.selectBoxOption = Object.assign({}, str);
                                }
                                if (_that.pagination.current_page==_that.quizInfo.number_of_questions){
                                    _that.itemA = 1;
                                    _that.isRepeat = false;
                                }else {
                                    _that.itemA = 0
                                }
                            })
                        });
                }

                else if (_that.pagination.current_page == _that.quizInfo.number_of_questions){
                    _that.isFinish = true;
                    _that.isRepeat = false;
                    _that.checkNextData();
                    // _that.finalizeData();
                }

                else{
                    pageUrl = pageUrl == undefined ? '/quiz-form/field-list/'+_that.quizInfo.quiz_form_id+'?page=1' : pageUrl;
                    axios.get(pageUrl)
                        .then(function (response) {
                            _that.isLoading= false;
                            if (_that.questionArray.includes(response.data.quiz_form_field_list.data['0'].id)) {
                                console.log('skip');
                                _that.getQuizFormField(pageUrl);
                            }else{
                                _that.quizFormFieldInfo = response.data.quiz_form_field_list.data;
                                _that.pagination  = response.data.quiz_form_field_list;

                                _that.questionArray.push(response.data.quiz_form_field_list.data['0'].id)
                                // console.log(_that.questionArray)

                                _that.quizFormFieldInfo.forEach(val =>{
                                    _that.resultArray.push(val.f_default_value);
                                    if (val.f_option_value !=null && val.f_option_value.search(',')){
                                        let str = val.f_option_value;
                                        str = str.split(",");

                                        // console.log("before"+ str);
                                        str = str.sort(() => Math.random() - 0.5);
                                        // _that.strToArr = Object.values(str);

                                        // console.log("after" + str);

                                        _that.selectBoxOption = Object.assign({}, str);
                                    }
                                    if (_that.pagination.current_page==_that.quizInfo.number_of_questions){
                                        _that.itemA = 1;
                                        _that.isRepeat = false;
                                    }else {
                                        _that.itemA = 0
                                    }
                                })
                            }


                        })
                }

            },
            getEachQuizMark(v1,v2){
                this.markCounter = v1/v2;
            },
            quizTimerStart(counter){
                let timer = counter*60;
                this.clearCounter = setInterval(()=>{
                    if (timer> 0){
                        --timer;
                        this.printTimer(timer)
                    }
                    else{
                        this.isRepeat = false;
                        this.checkNextData();
                    }
                },1e3)
            },
            printTimer(sec) {
                let hr = Math.floor(sec / 3600) % 24;
                let mm = Math.floor(sec / 60) % 60;
                let ss = sec % 60;
                let x = hr < 10? "0"+hr : hr;
                let y = mm < 10? "0"+mm : mm;
                let z = ss < 10? "0"+ss : ss;
                this.timeCounter = x+":"+y+":"+z;
            },
            detectTab(){
                $(window).on("blur",function () {
                    //do something
                    // console.log("You left this tab");
                    if (this.isFinish==true){
                        window.location.reload();
                    } else{
                        // console.log("you may proceed");
                    }

                })
            },
        },

        created() {
            let _that = this;
            if(sessionStorage.userInformation) {
                _that.userInformation = JSON.parse(sessionStorage.userInformation);
            }
            _that.detectTab();
            if (_that.$route.params.quiz_info){
                _that.quizInfo = JSON.parse(_that.$route.params.quiz_info);

                _that.article_list = _that.quizInfo.article_id.split(',');
                console.log(_that.article_list)
                _that.passMark =  ( _that.quizInfo.total_marks*40 )/100;
                let timer = _that.quizInfo.duration*60;
                _that.printTimer(timer);//total time
                _that.getQuizFormField();
                _that.quizTimerStart(_that.quizInfo.duration);//timeleft
                // _that.quizInfo = JSON.parse(localStorage.getItem("quiz_info"));
                _that.getEachQuizMark(_that.quizInfo.total_marks, _that.quizInfo.number_of_questions);
            }else{
                this.$router.push({ name: 'Quiz' })
            }
        },
    }
</script>

<style scoped>

</style>
