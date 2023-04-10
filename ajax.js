
M.plagiarism_plagium = {
    analizys: [],
    $: null,
    cmid: null
};

require([
    'jquery',
], ($) => {
    M.plagiarism_plagium.$ = $;
});

M.plagiarism_plagium.init = function (Y, cmid) {
    console.log("..:: PLAGIUM ::..");
    M.plagiarism_plagium.Y = Y;
    M.plagiarism_plagium.cmid = cmid;
};

M.plagiarism_plagium.prepare = (analizy) => {
    if (analizy && analizy.meta && analizy.meta.obj && analizy.meta.obj.data) {
        var datatAnalizy = analizy.meta.obj.data;

        if (analizy.similarity) {
            var similarity = M.plagiarism_plagium.$(`#plagium-report-${analizy.id}-similarity`);
            similarity.text(`${analizy.similarity}%`);

            if (analizy.similarity_label) {
                similarity.removeClass('badge-success');
                similarity.removeClass('badge-primary');
                similarity.removeClass('badge-success');
                similarity.removeClass('badge-warning');
                similarity.removeClass('badge-info');
                similarity.removeClass('badge-danger');
                similarity.addClass(analizy.similarity_label);
            }
        }

        if (analizy.similarity_max) {
            var similarityMax = M.plagiarism_plagium.$(`#plagium-report-${analizy.id}-similarity-max`);
            similarityMax.text(`${analizy.similarity_max}%`);

            if (analizy.similarity_max_label) {
                similarityMax.removeClass('badge-success');
                similarityMax.removeClass('badge-primary');
                similarityMax.removeClass('badge-success');
                similarityMax.removeClass('badge-warning');
                similarityMax.removeClass('badge-info');
                similarityMax.removeClass('badge-danger');
                similarityMax.addClass(analizy.similarity_max_label);
            }
        }

        if (analizy.similarity_risk) {
            var similarityRisk = M.plagiarism_plagium.$(`#plagium-report-${analizy.id}-similarity-risk`);
            similarityRisk.text(`${analizy.similarity_risk}%`);

            if (analizy.similarity_risk_label) {
                similarityRisk.removeClass('badge-success');
                similarityRisk.removeClass('badge-primary');
                similarityRisk.removeClass('badge-success');
                similarityRisk.removeClass('badge-warning');
                similarityRisk.removeClass('badge-info');
                similarityRisk.removeClass('badge-danger');
                similarityRisk.addClass(analizy.similarity_risk_label);
            }
        }

        if (datatAnalizy && datatAnalizy.search && datatAnalizy.search) {
            var status = datatAnalizy.search.status;

            var plagiumReportStatusReady = M.plagiarism_plagium.$(`#plagium-report-${analizy.id}-status-ready`);
            var plagiumReportStatusWaiting = M.plagiarism_plagium.$(`#plagium-report-${analizy.id}-status-waiting`);


            var plagiumReportBtnAnalizy = M.plagiarism_plagium.$(`#plagium-report-${analizy.id}-btn-analizy`);
            var plagiumReportBtnReport = M.plagiarism_plagium.$(`#plagium-report-${analizy.id}-btn-report`);

            if (status == "ready") {
                plagiumReportStatusReady.text(`${status}`);
                plagiumReportStatusReady.removeClass('d-none');
                plagiumReportStatusWaiting.addClass('d-none');

                plagiumReportBtnAnalizy.attr("disabled", false);
                plagiumReportBtnReport.attr("disabled", false);

                plagiumReportBtnAnalizy.addClass("btn-primary");
                plagiumReportBtnAnalizy.removeClass("btn-danger");
            } else {
                plagiumReportStatusWaiting.text(`${status}`);
                plagiumReportStatusReady.addClass('d-none');
                plagiumReportStatusWaiting.removeClass('d-none');

                plagiumReportBtnAnalizy.attr("disabled", true);
                plagiumReportBtnReport.attr("disabled", true);

                plagiumReportBtnAnalizy.addClass("btn-danger");
                plagiumReportBtnAnalizy.removeClass("btn-primary");
            }
        }
    }
}

M.plagiarism_plagium.request = function(analizyId, report = false, refresh = false, callbackAction = null) {
    console.log(`..:: PLAGIUM Request: ${analizyId} ::..`)
    var url = M.cfg.wwwroot + '/plagiarism/plagium/ajax.php';
    var callback = {
        method: 'get',
        context: this,
        sync: false,
        data: {
            'sesskey': M.cfg.sesskey,
            'data': Y.JSON.stringify({
                "id": analizyId,
                "report": report,
                "refresh": refresh,
                "cmid" : M.plagiarism_plagium.cmid
            })
        },
        on: {
            success: function (tid, response) {
                var data = Y.JSON.parse(response.responseText);
                var analizy = data.analizy;
                console.log(analizy)

                M.plagiarism_plagium.prepare(analizy);
                var exist = M.plagiarism_plagium.analizys.find(a => a.id == analizyId);
                if (!exist) {
                    M.plagiarism_plagium.analizys.push({id: analizyId, data: analizy, load: false})
                } else {
                    M.plagiarism_plagium.analizys = M.plagiarism_plagium.analizys.map(a => {
                        if (a.id == analizyId) {
                            return {id: analizyId, data: analizy, load: false};
                        }
                        return a;
                    })
                }

                if (callbackAction) {
                    callbackAction(data);
                }
            },
            failure: function (error) {
                console.log(error);
            }
        }
    };
    Y.io(url, callback);
}

M.plagiarism_plagium.report = function(analizyId) {
    if (!M.plagiarism_plagium.$) return;
    M.plagiarism_plagium.request(analizyId, true, false, (analizy) => {
        M.plagiarism_plagium.$(`#plagium-report-${analizyId}-modal`).modal("show");
        M.plagiarism_plagium.$(`#plagium-report-${analizyId}-modal .modal-body`).html(analizy.report);
    })
}

M.plagiarism_plagium.prepareRequest = function(analizyId) {
    if (M.plagiarism_plagium.analizys && M.plagiarism_plagium.analizys.length > 0) {
        var item = M.plagiarism_plagium.analizys.find(itemAnalizy => itemAnalizy.id === analizyId);

        if (item.load) {
            return;
        };

        M.plagiarism_plagium.analizys = M.plagiarism_plagium.analizys.map(itemAnalizy => {
            if (itemAnalizy.id === analizyId) {
                itemAnalizy.load = true;
                return itemAnalizy;
            }
            return itemAnalizy;
        })

        if (item) {
            var analizy = item.data;
            if (analizy && analizy.meta && analizy.meta.obj && analizy.meta.obj.data) {
                var datatAnalizy = analizy.meta.obj.data;
                if (datatAnalizy && datatAnalizy.search && datatAnalizy.search) {
                    var status = datatAnalizy.search.status;
                    if (status !== "ready") {
                        M.plagiarism_plagium.request(analizy.id);
                    }
                }
            }
        }
    }
}

setInterval(function () {
    M.plagiarism_plagium.analizys.map(a => {
        M.plagiarism_plagium.prepareRequest(a.id);
    });
}, 60000 / 2);