
cc.Class({
    extends: cc.Component,

    properties: {
        atlasBtn: cc.Button,
        discoverBtn: cc.Button,
        storeBtn: cc.Button,
    },

    init () {
        this.currentScene = cc.director.getRunningScene();
    },

    onLoad () {
        this.atlasBtn.node.on('click', (button) =>{
            this.switchScene('home');
        }, this);

        this.discoverBtn.node.on('click', (button) =>{
            this.switchScene('discover');
        }, this);

        this.storeBtn.node.on('click', (button) =>{
            this.switchScene('store');
        }, this);
    },

    start () {
        this.init();
        console.log(this.currentScene.name);

        switch (this.currentScene.name) {
            case 'home':
                this.atlasBtn.node.runAction(cc.scaleTo(0, 1.1));
                break;
            case 'discover':
                this.discoverBtn.node.runAction(cc.scaleTo(0, 1.1));
                break;
            case 'store':
                this.storeBtn.node.runAction(cc.scaleTo(0, 1.1));
                break;
            default:
                break;
        }
    },

    /**
     * 检查场景
     * 
     * @param {string} scene 
     */
    checkScene (scene) {
        return this.currentScene.name == scene ? true : false;
    },

    /**
     * 切换场景
     * 
     * @param {sting} scene 
     */
    switchScene (scene) {
        if (!this.checkScene(scene)) {
            cc.director.getRunningScene().runAction(
                cc.sequence(
                    cc.scaleTo(0.1, 0.01),
                    cc.callFunc(() =>{
                        cc.director.loadScene(scene);
                    }, this)
                )
            );
        }
    },
});
