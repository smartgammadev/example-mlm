var itemActions = {
    "audience" : {
        "create" : function(audience_id) {
            return Routing.generate("admin_success_salesgenerator_audience_create");
        },
        "edit" : function(audience_id) {
            return Routing.generate("admin_success_salesgenerator_audience_edit", {"id" : audience_id });
        },
        "delete" : function(audience_id) {
            return Routing.generate("admin_success_salesgenerator_audience_delete", {"id" : audience_id });
        }
    },
    "question" : {
        "create" : function(question_id) {
            return Routing.generate("admin_success_salesgenerator_question_create");
        },
        "edit" : function(question_id) {
            return Routing.generate("admin_success_salesgenerator_question_edit", {"id" : question_id });
        },
        "delete" : function(question_id) {
            return Routing.generate("admin_success_salesgenerator_question_delete", {"id" : question_id });
        }
    },
    "answer" : {
        "edit" : function(answer_id) {
            return Routing.generate("admin_success_salesgenerator_answer_edit", {"id" : answer_id });
        },
        "delete" : function(answer_id) {
            return Routing.generate("admin_success_salesgenerator_answer_delete", {"id" : answer_id });
        }
    }
};

function contextMenu(node) {                
    var type = $("#jstree").jstree(true).get_type(node), id = node.id.substring(type.length + 1);
    var menuItems = {
        editItem : {
            label : "Edit " + type,
            action : function () {
                window.location.href = itemActions[type].edit(id);
            }
        },
        deleteItem : {
            label : "Delete " + type,
            action : function () {
                window.location.href = itemActions[type].delete(id);
            }
        },
        createAudience : {
            label : 'Create audience',
            action : function () {
                window.location.href = itemActions['audience'].create();
            }
        },
        createQuestion : {
            label : "Create question",
            action : function () {
                window.location.href = itemActions['question'].create(id);
            }
        }
    };

    switch(type) {
        case "audience":
            delete menuItems.createAudience;
            break;
        case "question":
            delete menuItems.createAudience;
            delete menuItems.createQuestion;
            break;
        case "answer":
            delete menuItems.editItem;
            delete menuItems.createAudience;
            delete menuItems.createQuestion;
            break;
        case "default":
            delete menuItems.createQuestion;
            delete menuItems.editItem;
            delete menuItems.deleteItem;
            break;
    };

    return menuItems;
}
    
$("#jstree").on('select_node.jstree', function(event, data) {
    var jstree = $('#jstree').jstree(true), parent = $('#jstree').jstree('get_selected')[0];
    
    if (jstree.get_children_dom(data.node).length === 0 ) {
        $.ajax({
            url : Routing.generate("admin_sonata_get_question"),
            type : 'POST',
            data : {
               'question_id' : data.node.li_attr["data-next"]
            },
            dataType: 'JSON',
            success : function(question) {
                // Limit text
                var questionText = question.text.length > 80 ? question.text.substring(0,80) + '...' : question.text,
                questionNode = {"id" : "question-" + question.id, "text" : questionText, "state" : { "opened" : "true"}, "type" : "question"},
                newParent = jstree.create_node(parent, questionNode);
                
                $('#jstree').jstree('open_node', '#'+parent);
                if (question.answers) {
                    question.answers.forEach(function(answer, index) {
                        answerNode = { "id" : "answer-" + answer.id, "li_attr" : { "data-next" : answer.nextQuestion }, 
                            "text" : answer.text, "type": "answer" };
                        jstree.create_node(newParent, answerNode);
                    });
                }
            }
       })
    }
});