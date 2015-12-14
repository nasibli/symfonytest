<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Школа китайского языка</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/ext/css/ext-all.css" />
    <script type="text/javascript" src="/ext/ext-base.js"></script>
    <script type="text/javascript" src="/ext/ext-all.js"></script>
    <script type="text/javascript" src="/Mine.js"></script>
    <script type="text/javascript" src="/App.js"></script>
</head>
<body>
<div id="teacherWindow"> </div>
<div id="pupilWindow"> </div>
<div id="teacherPupilAddWindow"> </div>
<div id="teacherPupilViewWindow"> </div>
<script type="text/javascript">

    Ext.onReady(function(){

        var teacherTab = createTeacherTab();

        var pupilTab   = createPupilTab();

        var pupilPairTab =  createPupilPairTab();

        var view = new Ext.Viewport({
            layout: 'border',
            items: [ {
                region: 'center',
                xtype: 'tabpanel',

                activeTab: 0,
                itemId: 'tabs',
                items: [
                    {
                        'title': 'Учителя',
                        items: [teacherTab]
                    },
                    {
                        title: 'Ученики',
                        items: [pupilTab]
                    }/*,
                    {
                        title: 'Ученики(общие)',
                        items: [pupilPairTab]
                    }*/

                ]

            }]
        });

        teacherTab.setHeight(view.getHeight()-28);
        pupilTab.setHeight(view.getHeight()-28);
        /*pupilPairTab.setHeight(view.getHeight()-28);*/
        view.doLayout();

    });

</script>

</body>
</html>